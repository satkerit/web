<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BlockSuspiciousRequests
{
    /**
     * Routes to exclude from suspicious pattern checking
     */
    protected array $excludedRoutes = [
        'storage/*',
        'login',
        'logout',
        'register',
        'password/*',
        'forgot-password',
        'reset-password/*',
        'livewire/*',
        'sanctum/*',
        '_ignition/*',
        'admin/storage/*',
        'admin/company-info',
        'admin/*/upload*',
    ];

    protected array $suspiciousPatterns = [
        // SQL Injection patterns - more specific to avoid false positives
        '/union\s+all\s+select/i',
        '/union\s+select/i',
        '/select\s+.*\s+from\s+.*\s+where\s+.*=/i',
        '/insert\s+into\s+\w+\s*\(/i',
        '/drop\s+table\s+/i',
        '/delete\s+from\s+\w+\s+where/i',
        '/update\s+\w+\s+set\s+\w+\s*=/i',
        '/exec\s*\(\s*xp_/i',
        '/;\s*drop\s/i',
        '/;\s*delete\s/i',
        '/\'\s*or\s+\'1\'\s*=\s*\'1/i',
        '/\'\s*or\s+1\s*=\s*1/i',

        // XSS patterns
        '/<script[^>]*>.*<\/script>/is',
        '/javascript\s*:\s*[a-z]/i',

        // Path traversal - only in URL path, not in data
        // Removed to avoid false positives

        // Common attack paths
        '/wp-admin/i',
        '/wp-login\.php/i',
        '/xmlrpc\.php/i',
        '/phpmyadmin/i',
        '/\.env$/i',
        '/\.git\//i',
        '/\.htaccess/i',
        '/etc\/passwd/i',
        '/proc\/self/i',
    ];

    protected array $suspiciousAgents = [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'zgrab',
        'havij',
        'acunetix',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Skip for excluded routes
            if ($this->isExcludedRoute($request)) {
                return $next($request);
            }

            // Get security settings with fallback
            $settings = $this->getSettings();

            // Skip if feature is disabled or settings not available
            if (!$settings || !$settings->enable_suspicious_blocking) {
                return $next($request);
            }

            $ip = $request->ip();

            // Check whitelist first
            if ($this->isWhitelisted($ip, $settings)) {
                return $next($request);
            }

            // Check if IP is blocked (from database)
            if ($this->isIpBlocked($ip)) {
                return $this->blockResponse($request);
            }

            // Only check URL path for suspicious patterns (not POST data to avoid false positives)
            if ($this->hasSuspiciousUrl($request)) {
                $this->recordSuspiciousActivity($request, $ip, 'suspicious_url', $settings);
                return $this->blockResponse($request);
            }

            // Check for suspicious user agents
            if ($this->hasSuspiciousUserAgent($request)) {
                $this->recordSuspiciousActivity($request, $ip, 'suspicious_agent', $settings);
                return $this->blockResponse($request);
            }

            return $next($request);
        } catch (\Exception $e) {
            // If any error occurs, let the request through to avoid blocking legitimate users
            Log::error('BlockSuspiciousRequests error: ' . $e->getMessage());
            return $next($request);
        }
    }

    protected function isExcludedRoute(Request $request): bool
    {
        foreach ($this->excludedRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }
        return false;
    }

    protected function isIpBlocked(string $ip): bool
    {
        try {
            return BlockedIp::isBlocked($ip);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getSettings(): ?SecuritySetting
    {
        try {
            return Cache::remember('security_settings', 300, function () {
                return SecuritySetting::first();
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function isWhitelisted(string $ip, SecuritySetting $settings): bool
    {
        $whitelist = $settings->getWhitelistArray();
        return in_array($ip, $whitelist);
    }

    protected function hasSuspiciousUrl(Request $request): bool
    {
        $checkStrings = [
            $request->path(),
            urldecode($request->getRequestUri()),
        ];

        foreach ($checkStrings as $string) {
            if (empty($string)) continue;

            foreach ($this->suspiciousPatterns as $pattern) {
                if (@preg_match($pattern, $string)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function hasSuspiciousUserAgent(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (empty($userAgent)) {
            return false;
        }

        foreach ($this->suspiciousAgents as $agent) {
            if (str_contains($userAgent, strtolower($agent))) {
                return true;
            }
        }

        return false;
    }

    protected function recordSuspiciousActivity(Request $request, string $ip, string $type, SecuritySetting $settings): void
    {
        try {
            $key = "suspicious_count:{$ip}";
            $count = Cache::increment($key);

            if ($count === 1) {
                Cache::put($key, 1, now()->addHour());
            }

            // Increment attack counter for today
            $todayKey = 'security_attacks_' . date('Y-m-d');
            Cache::increment($todayKey);
            Cache::put('security_attacks_today', Cache::get($todayKey, 0), now()->endOfDay());

            // Log if enabled
            if ($settings->log_security_events) {
                Log::warning('Suspicious activity detected', [
                    'ip' => $ip,
                    'type' => $type,
                    'path' => $request->path(),
                    'user_agent' => $request->userAgent(),
                    'count' => $count,
                ]);
            }

            // Block IP after reaching threshold
            if ($count >= $settings->block_threshold) {
                BlockedIp::blockIp($ip, "Auto-blocked: {$type}", $settings->block_duration_hours);

                if ($settings->log_security_events) {
                    Log::alert('IP auto-blocked', [
                        'ip' => $ip,
                        'type' => $type,
                        'count' => $count,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error recording suspicious activity: ' . $e->getMessage());
        }
    }

    protected function blockResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Access Denied'], 403);
        }

        return response()->view('errors.403', [], 403);
    }
}
