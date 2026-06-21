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
    protected array $excludedRoutes = [
        'storage/*', 'logout', 'sanctum/*', '_ignition/*',
        'admin/storage/*', 'admin/*/upload*', 'admin/company-info*',
        'admin/news*', 'admin/products*', 'admin/hero-slides*',
        'admin/why-choose-us*', 'admin/board-members*', 'admin/offices*',
        'admin/careers*', 'admin/brochures*', 'admin/auctions*', 'admin/reports*'
    ];

    protected array $suspiciousPatterns;
    protected array $suspiciousAgents;

    public function __construct()
    {
        $config = config('security-patterns');
        $this->suspiciousPatterns = $config['url_attack_patterns'] ?? [];
        $this->suspiciousAgents = $config['scanner_agents'] ?? [];
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            if ($this->isExcludedRoute($request)) {
                return $next($request);
            }

            $settings = $this->loadSettings();
            if (!$settings?->enable_suspicious_blocking) {
                return $next($request);
            }

            $ip = $request->ip();

            if ($this->isWhitelisted($ip, $settings)) {
                return $next($request);
            }

            if ($this->isIpBlocked($ip)) {
                return $this->blockResponse($request);
            }

            if ($this->hasSuspiciousUrl($request)) {
                $this->recordSuspiciousActivity($request, $ip, 'suspicious_url', $settings);
                return $this->blockResponse($request);
            }

            if ($this->hasSuspiciousUserAgent($request)) {
                $this->recordSuspiciousActivity($request, $ip, 'suspicious_agent', $settings);
                return $this->blockResponse($request);
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('BlockSuspiciousRequests error: ' . $e->getMessage());
            return $next($request);
        }
    }

    protected function isExcludedRoute(Request $request): bool
    {
        foreach ($this->excludedRoutes as $route) {
            if ($request->is($route)) return true;
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

    protected function loadSettings(): ?SecuritySetting
    {
        try {
            return Cache::remember('security_settings', 300, fn() => SecuritySetting::first());
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function isWhitelisted(string $ip, SecuritySetting $settings): bool
    {
        return in_array($ip, $settings->getWhitelistArray());
    }

    protected function hasSuspiciousUrl(Request $request): bool
    {
        $checkStrings = array_filter([
            $request->path(),
            urldecode($request->getRequestUri()),
        ]);

        foreach ($checkStrings as $string) {
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
        if (empty($userAgent)) return false;

        foreach ($this->suspiciousAgents as $agent) {
            if (str_contains($userAgent, strtolower($agent))) return true;
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

            $todayKey = 'security_attacks_' . date('Y-m-d');
            Cache::increment($todayKey);
            Cache::put('security_attacks_today', Cache::get($todayKey, 0), now()->endOfDay());

            if ($settings->log_security_events) {
                Log::warning('Suspicious activity detected', [
                    'ip' => $ip,
                    'type' => $type,
                    'path' => $request->path(),
                    'user_agent' => $request->userAgent(),
                    'count' => $count,
                ]);
            }

            if ($count >= $settings->block_threshold) {
                BlockedIp::blockIp($ip, "Auto-blocked: {$type}", $settings->block_duration_hours);
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