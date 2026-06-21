<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('audit.view');

        $query = AuditTrail::with('user')->latest();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                    ->orWhere('user_name', 'like', '%' . $request->search . '%')
                    ->orWhere('ip_address', 'like', '%' . $request->search . '%');
            });
        }

        $audits = $query->paginate(25)->withQueryString();

        // Get unique actions for filter
        $actions = AuditTrail::distinct()->pluck('action');

        // Get users who have audit logs
        $users = \App\Models\User::whereIn('id', AuditTrail::distinct()->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        // Get model types for filter
        $modelTypes = AuditTrail::distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type))
            ->unique()
            ->values();

        return view('admin.audit-trails.index', compact('audits', 'actions', 'users', 'modelTypes'));
    }

    public function show(AuditTrail $auditTrail)
    {
        $this->authorizeView('audit.view');

        $auditTrail->load('user');
        return view('admin.audit-trails.show', compact('auditTrail'));
    }

    public function clear(Request $request)
    {
        $this->authorizeAny(['audit.clear']);

        $days = $request->input('days', 90);

        try {
            $deleted = AuditTrail::where('created_at', '<', now()->subDays($days))->delete();

            AuditTrail::log('clear_logs', "Menghapus {$deleted} log audit yang lebih dari {$days} hari");

            return redirect()->route('admin.audit-trails.index')
                ->with('success', "Berhasil menghapus {$deleted} log audit yang lebih dari {$days} hari.");
        } catch (\Exception $e) {
            return redirect()->route('admin.audit-trails.index')
                ->with('error', 'Gagal menghapus log audit: ' . $e->getMessage());
        }
    }
}
