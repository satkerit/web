<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecuritySetting;
use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SecuritySettingController extends Controller
{
    public function index()
    {
        $settings = SecuritySetting::getSettings();
        
        // Get blocked IPs statistics
        $blockedIpsCount = BlockedIp::count();
        $permanentBlocksCount = BlockedIp::where('is_permanent', true)->count();
        $temporaryBlocksCount = BlockedIp::where('is_permanent', false)
            ->where('blocked_until', '>', now())
            ->count();
        
        // Get recent blocked IPs
        $recentBlocks = BlockedIp::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.settings.security', compact(
            'settings',
            'blockedIpsCount',
            'permanentBlocksCount',
            'temporaryBlocksCount',
            'recentBlocks'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Rate Limiting
            'rate_limit_web' => 'required|integer|min:10|max:1000',
            'rate_limit_admin' => 'required|integer|min:10|max:500',
            'rate_limit_login' => 'required|integer|min:1|max:20',
            'rate_limit_password_reset' => 'required|integer|min:1|max:10',
            'rate_limit_download' => 'required|integer|min:5|max:100',
            
            // IP Blocking
            'block_threshold' => 'required|integer|min:3|max:50',
            'block_duration_hours' => 'required|integer|min:1|max:168',
            'ip_whitelist' => 'nullable|string',
            'ip_blacklist' => 'nullable|string',
            
            // Security Features
            'enable_suspicious_blocking' => 'boolean',
            'enable_rate_limiting' => 'boolean',
            'log_security_events' => 'boolean',
            
            // Session Settings
            'session_lifetime' => 'required|integer|min:30|max:1440',
            'idle_timeout' => 'required|integer|min:5|max:480',
            'idle_warning' => 'required|integer|min:1|max:60',
            'auto_extend_session' => 'boolean',
            'enable_session_tracking' => 'boolean',
        ]);

        // Convert checkboxes
        $validated['enable_suspicious_blocking'] = $request->has('enable_suspicious_blocking');
        $validated['enable_rate_limiting'] = $request->has('enable_rate_limiting');
        $validated['log_security_events'] = $request->has('log_security_events');
        $validated['auto_extend_session'] = $request->has('auto_extend_session');
        $validated['enable_session_tracking'] = $request->has('enable_session_tracking');

        // Validate IP addresses
        if ($request->filled('ip_whitelist')) {
            $ips = array_filter(array_map('trim', explode("\n", $request->ip_whitelist)));
            foreach ($ips as $ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                    return back()->withErrors(['ip_whitelist' => "IP tidak valid: {$ip}"])->withInput();
                }
            }
        }

        if ($request->filled('ip_blacklist')) {
            $ips = array_filter(array_map('trim', explode("\n", $request->ip_blacklist)));
            foreach ($ips as $ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                    return back()->withErrors(['ip_blacklist' => "IP tidak valid: {$ip}"])->withInput();
                }
            }
        }

        // Validate idle_warning is less than idle_timeout
        if ($validated['idle_warning'] >= $validated['idle_timeout']) {
            return back()->withErrors(['idle_warning' => 'Idle warning harus lebih kecil dari idle timeout'])->withInput();
        }

        $settings = SecuritySetting::getSettings();
        $settings->update($validated);

        // Clear cache
        SecuritySetting::clearCache();
        Cache::flush();

        return redirect()->route('admin.settings.security')
            ->with('success', 'Pengaturan keamanan berhasil diperbarui');
    }

    public function blockedIps()
    {
        $blockedIps = BlockedIp::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.settings.blocked-ips', compact('blockedIps'));
    }

    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();
        
        return redirect()->route('admin.settings.blocked-ips')
            ->with('success', 'IP berhasil di-unblock');
    }

    public function blockIp(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255',
            'is_permanent' => 'boolean',
            'duration_hours' => 'required_if:is_permanent,false|integer|min:1|max:168',
        ]);

        $validated['is_permanent'] = $request->has('is_permanent');
        
        if (!$validated['is_permanent']) {
            $validated['blocked_until'] = now()->addHours($validated['duration_hours']);
        }

        BlockedIp::updateOrCreate(
            ['ip_address' => $validated['ip_address']],
            $validated
        );

        return redirect()->route('admin.settings.blocked-ips')
            ->with('success', 'IP berhasil diblokir');
    }

    public function clearExpiredBlocks()
    {
        $deleted = BlockedIp::where('is_permanent', false)
            ->where('blocked_until', '<', now())
            ->delete();

        return redirect()->route('admin.settings.blocked-ips')
            ->with('success', "Berhasil menghapus {$deleted} blokir yang sudah kadaluarsa");
    }

    public function testSecurity()
    {
        $settings = SecuritySetting::getSettings();
        
        $tests = [
            'Rate Limiting' => [
                'Web' => $settings->rate_limit_web . ' requests/minute',
                'Admin' => $settings->rate_limit_admin . ' requests/minute',
                'Login' => $settings->rate_limit_login . ' attempts/minute',
            ],
            'IP Blocking' => [
                'Threshold' => $settings->block_threshold . ' failed attempts',
                'Duration' => $settings->block_duration_hours . ' hours',
                'Whitelist' => count($settings->getWhitelistArray()) . ' IPs',
                'Blacklist' => count($settings->getBlacklistArray()) . ' IPs',
            ],
            'Features' => [
                'Suspicious Blocking' => $settings->enable_suspicious_blocking ? 'Enabled' : 'Disabled',
                'Rate Limiting' => $settings->enable_rate_limiting ? 'Enabled' : 'Disabled',
                'Security Logging' => $settings->log_security_events ? 'Enabled' : 'Disabled',
            ],
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Security settings test completed',
            'tests' => $tests,
            'current_ip' => request()->ip(),
            'is_whitelisted' => in_array(request()->ip(), $settings->getWhitelistArray()),
            'is_blacklisted' => in_array(request()->ip(), $settings->getBlacklistArray()),
        ]);
    }
}
