<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CustomerComplaintStatusUpdateMail;
use App\Models\CompanyInfo;
use App\Models\CustomerComplaint;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerComplaintController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('complaints.view');

        $query = CustomerComplaint::with('handler')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('subject', 'like', '%' . $request->search . '%')
                    ->orWhere('account_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => CustomerComplaint::where('status', 'pending')->count(),
            'in_progress' => CustomerComplaint::where('status', 'in_progress')->count(),
            'resolved' => CustomerComplaint::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return view('admin.customer-complaints.index', compact('complaints', 'stats'));
    }

    public function show(CustomerComplaint $customerComplaint)
    {
        $this->authorizeView('complaints.view');

        $customerComplaint->load('handler');
        return view('admin.customer-complaints.show', compact('customerComplaint'));
    }

    public function update(Request $request, CustomerComplaint $customerComplaint)
    {
        $this->authorizeAny(['complaints.manage']);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high',
            'resolution' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $customerComplaint->status;

        if ($validated['status'] === 'resolved' && $customerComplaint->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        // Set handler if status changes to in_progress
        if ($validated['status'] === 'in_progress' && !$customerComplaint->handled_by) {
            $validated['handled_by'] = Auth::id();
        }

        $customerComplaint->update($validated);

        // Send email notification if status changed
        if ($oldStatus !== $validated['status'] && $customerComplaint->email) {
            try {
                $companyInfo = CompanyInfo::first();
                Mail::to($customerComplaint->email)->send(new CustomerComplaintStatusUpdateMail(
                    $customerComplaint,
                    $oldStatus,
                    $validated['admin_notes'] ?? null,
                    $validated['resolution'] ?? null,
                    $companyInfo
                ));
            } catch (\Exception $e) {
                Log::error('Failed to send customer complaint status update email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.customer-complaints.show', $customerComplaint)
            ->with('success', 'Pengaduan nasabah berhasil diperbarui.');
    }

    public function destroy(CustomerComplaint $customerComplaint)
    {
        $this->authorizeAny(['complaints.manage']);

        $customerComplaint->delete();

        return redirect()->route('admin.customer-complaints.index')
            ->with('success', 'Pengaduan nasabah berhasil dihapus.');
    }
}
