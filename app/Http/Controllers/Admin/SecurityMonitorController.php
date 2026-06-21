<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\SecurityLog;
use App\Models\SecuritySetting;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SecurityMonitorController extends Controller
{
    use AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('security.view');

        $stats = SecurityLog::getStatistics(7);

        $threats = SecurityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        $blockedIps = BlockedIp::where(function ($query) {
            $query->where('is_permanent', true)
                ->orWhere('blocked_until', '>', now());
        })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $realTimeStats = [
            'threats_today' => SecurityLog::getTodayCount(),
            'blocked_today' => SecurityLog::whereDate('created_at', today())->where('was_blocked', true)->count(),
            'active_blocks' => BlockedIp::where(function ($query) {
                $query->where('is_permanent', true)
                    ->orWhere('blocked_until', '>', now());
            })->count(),
        ];

        return view('admin.security.monitor', compact('stats', 'threats', 'blockedIps', 'realTimeStats'));
    }

    public function show(SecurityLog $securityLog)
    {
        $this->authorizeView('security.view');

        $relatedThreats = SecurityLog::where('ip_address', $securityLog->ip_address)
            ->where('id', '!=', $securityLog->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.security.show', compact('securityLog', 'relatedThreats'));
    }

    public function chartData(Request $request)
    {
        $this->authorizeView('security.view');

        $days = $request->input('days', 7);
        $stats = SecurityLog::getStatistics($days);

        return response()->json(['success' => true, 'data' => $stats]);
    }

    public function blockIp(Request $request)
    {
        $this->authorizeEdit('security.manage');

        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255',
            'duration' => 'required|integer|min:1|max:8760',
            'permanent' => 'boolean',
        ]);

        try {
            $ip = $request->input('ip_address');
            $reason = $request->input('reason', 'Manually blocked by admin');
            $duration = $request->input('duration', 24);
            $permanent = $request->boolean('permanent');

            if ($permanent) {
                BlockedIp::updateOrCreate(
                    ['ip_address' => $ip],
                    ['reason' => $reason, 'is_permanent' => true, 'blocked_until' => null]
                );
            } else {
                BlockedIp::blockIp($ip, $reason, $duration);
            }

            SecurityLog::logThreat($ip, 'blocked_ip', request()->fullUrl(), 'Manual block', $reason, 'high', true);

            return response()->json(['success' => true, 'message' => "IP {$ip} berhasil diblokir."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memblokir IP: ' . $e->getMessage()], 500);
        }
    }

    public function unblockIp(Request $request, string $ip)
    {
        $this->authorizeEdit('security.manage');

        try {
            BlockedIp::where('ip_address', $ip)->delete();
            Cache::forget("blocked_ip:{$ip}");

            return response()->json(['success' => true, 'message' => "IP {$ip} berhasil dibuka blokirnya."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuka blokir IP: ' . $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $this->authorizeView('security.view');

        $days = $request->input('days', 7);

        $logs = SecurityLog::where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->get();

        $filename = 'security_logs_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Timestamp', 'IP Address', 'Threat Type', 'Threat Level',
                'Request Method', 'Request URL', 'User Agent', 'Was Blocked', 'Matched Pattern', 'User ID']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id, $log->created_at->format('Y-m-d H:i:s'), $log->ip_address,
                    $log->threat_type, $log->threat_level, $log->request_method, $log->request_url,
                    $log->user_agent, $log->was_blocked ? 'Yes' : 'No', $log->matched_pattern, $log->user_id,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cleanup(Request $request)
    {
        $this->authorizeDelete('security.manage');

        $request->validate(['days' => 'required|integer|min:7|max:365']);

        try {
            $deleted = SecurityLog::cleanup($request->input('days'));

            return response()->json(['success' => true, 'message' => "{$deleted} log lama berhasil dihapus."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus log: ' . $e->getMessage()], 500);
        }
    }

    public function clearExpiredBlocks()
    {
        $this->authorizeEdit('security.manage');

        try {
            $deleted = BlockedIp::where('is_permanent', false)
                ->where('blocked_until', '<', now())
                ->delete();

            return response()->json(['success' => true, 'message' => "{$deleted} blokir kadaluarsa berhasil dihapus."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus blokir: ' . $e->getMessage()], 500);
        }
    }

    public function threatsByIp(Request $request, string $ip)
    {
        $this->authorizeView('security.view');

        $threats = SecurityLog::where('ip_address', $ip)
            ->orderByDesc('created_at')->limit(50)->get();

        return response()->json([
            'success' => true,
            'data' => $threats,
            'total' => SecurityLog::where('ip_address', $ip)->count(),
        ]);
    }
}