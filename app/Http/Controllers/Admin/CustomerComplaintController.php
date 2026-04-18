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
use Carbon\Carbon;

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

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
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

    public function print(Request $request)
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

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $complaints = $query->get();
        $companyInfo = CompanyInfo::getInfo();

        $stats = [
            'total'       => $complaints->count(),
            'pending'     => $complaints->where('status', 'pending')->count(),
            'in_progress' => $complaints->where('status', 'in_progress')->count(),
            'resolved'    => $complaints->whereIn('status', ['resolved', 'closed'])->count(),
            'high'        => $complaints->where('priority', 'high')->count(),
            'medium'      => $complaints->where('priority', 'medium')->count(),
            'low'         => $complaints->where('priority', 'low')->count(),
        ];

        $filters = [
            'status'    => $request->status,
            'category'  => $request->category,
            'priority'  => $request->priority,
            'date_from' => $request->date_from,
            'date_to'   => $request->date_to,
            'search'    => $request->search,
        ];

        $printedAt = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm');
        $printedBy = auth()->user()->name;

        return view('admin.customer-complaints.print', compact(
            'complaints', 'companyInfo', 'stats', 'filters', 'printedAt', 'printedBy'
        ));
    }

    public function printSingle(CustomerComplaint $customerComplaint)
    {
        $this->authorizeView('complaints.view');

        $customerComplaint->load('handler');
        $companyInfo = CompanyInfo::getInfo();
        $printedAt   = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm');
        $printedBy   = auth()->user()->name;

        return view('admin.customer-complaints.print-single', compact(
            'customerComplaint', 'companyInfo', 'printedAt', 'printedBy'
        ));
    }
}
