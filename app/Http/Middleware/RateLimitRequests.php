<?php

namespace App\Http\Middleware;

use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitRequests
{
    public function handle(Request $request, Closure $next, string $type = 'general'): Response
    {
        $settings = $this->getSettings();

        // Skip if rate limiting is disabled
        if (!$settings->enable_rate_limiting) {
            return $next($request);
        }

        // Check whitelist
        if ($this->isWhitelisted($request->ip(), $settings)) {
            return $next($request);
        }

        $key = $this->resolveRequestKey($request, $type);
        $maxAttempts = $this->getMaxAttempts($type, $settings);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            if ($settings->log_security_events) {
                Log::warning('Rate limit exceeded', [
                    'type' => $type,
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                ]);
            }

            return response()->json([
                'message' => 'Terlalu banyak permintaan. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
                'retry_after' => $seconds,
            ], 429)->withHeaders([
                'Retry-After' => $seconds,
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        RateLimiter::hit($key, 60);

        $response = $next($request);

        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);
    }

    protected function getSettings(): SecuritySetting
    {
        return Cache::remember('security_settings', 300, function () {
            return SecuritySetting::first() ?? new SecuritySetting();
        });
    }

    protected function isWhitelisted(string $ip, SecuritySetting $settings): bool
    {
        return in_array($ip, $settings->getWhitelistArray());
    }

    protected function resolveRequestKey(Request $request, string $type): string
    {
        $ip = $request->ip();
        $userId = $request->user()?->id ?? 'guest';

        return match ($type) {
            'login' => "rate_login:{$ip}",
            'admin' => "rate_admin:{$ip}:{$userId}",
            'download' => "rate_download:{$ip}",
            'password_reset' => "rate_password:{$ip}",
            default => "rate_web:{$ip}",
        };
    }

    protected function getMaxAttempts(string $type, SecuritySetting $settings): int
    {
        return match ($type) {
            'login' => $settings->rate_limit_login,
            'admin' => $settings->rate_limit_admin,
            'download' => $settings->rate_limit_download,
            'password_reset' => $settings->rate_limit_password_reset,
            default => $settings->rate_limit_web,
        };
    }
}
