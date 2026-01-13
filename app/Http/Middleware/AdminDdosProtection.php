<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AdminDdosProtection
{
    /**
     * Rate limit configurations
     */
    protected array $limits = [
        'requests_per_minute' => 60,      // Max requests per minute
        'requests_per_second' => 5,       // Max requests per second (burst protection)
        'requests_per_hour' => 1000,      // Max requests per hour
        'failed_requests_limit' => 10,    // Max failed requests before temp block
    ];

    /**
     * Block durations in minutes
     */
    protected array $blockDurations = [
        1 => 5,      // First violation: 5 minutes
        2 => 15,     // Second violation: 15 minutes
        3 => 60,     // Third violation: 1 hour
        4 => 360,    // Fourth violation: 6 hours
        5 => 1440,   // Fifth+ violation: 24 hours
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userId = $request->user()?->id;

        // Check if IP is permanently blocked
        if ($this->isIpBlocked($ip)) {
            return $this->blockedResponse($request, 'IP Anda telah diblokir.');
        }

        // Check if temporarily blocked
        if ($this->isTemporarilyBlocked($ip)) {
            $remaining = $this->getBlockTimeRemaining($ip);
            return $this->blockedResponse($request, "Terlalu banyak request. Coba lagi dalam {$remaining} menit.");
        }

        // Load custom limits from settings if available
        $this->loadSettingsLimits();

        // Check burst protection (requests per second)
        if (!$this->checkBurstLimit($ip)) {
            $this->recordViolation($ip, 'burst');
            return $this->rateLimitResponse($request, 'Terlalu cepat! Mohon tunggu sebentar.');
        }

        // Check per-minute rate limit
        if (!$this->checkMinuteLimit($ip, $userId)) {
            $this->recordViolation($ip, 'minute');
            return $this->rateLimitResponse($request, 'Rate limit tercapai. Coba lagi dalam 1 menit.');
        }

        // Check per-hour rate limit
        if (!$this->checkHourLimit($ip, $userId)) {
            $this->recordViolation($ip, 'hour');
            return $this->rateLimitResponse($request, 'Batas request per jam tercapai.');
        }

        // Process request
        $response = $next($request);

        // Track failed responses (4xx, 5xx)
        if ($response->getStatusCode() >= 400) {
            $this->trackFailedRequest($ip);
        }

        // Add rate limit headers
        return $this->addRateLimitHeaders($response, $ip, $userId);
    }

    protected function isIpBlocked(string $ip): bool
    {
        try {
            return BlockedIp::isBlocked($ip);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function isTemporarilyBlocked(string $ip): bool
    {
        return Cache::has("admin_ddos_block:{$ip}");
    }

    protected function getBlockTimeRemaining(string $ip): int
    {
        $blockedUntil = Cache::get("admin_ddos_block:{$ip}");
        if (!$blockedUntil) return 0;

        return max(0, ceil((strtotime($blockedUntil) - time()) / 60));
    }

    protected function loadSettingsLimits(): void
    {
        try {
            $settings = Cache::remember('security_settings', 300, function () {
                return SecuritySetting::first();
            });

            if ($settings) {
                $this->limits['requests_per_minute'] = $settings->rate_limit_admin ?? 60;
            }
        } catch (\Exception $e) {
            // Use defaults
        }
    }

    protected function checkBurstLimit(string $ip): bool
    {
        $key = "admin_burst:{$ip}";
        $limit = $this->limits['requests_per_second'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 1); // 1 second decay
        return true;
    }

    protected function checkMinuteLimit(string $ip, ?int $userId): bool
    {
        $key = "admin_minute:{$ip}:" . ($userId ?? 'guest');
        $limit = $this->limits['requests_per_minute'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 60); // 60 seconds decay
        return true;
    }

    protected function checkHourLimit(string $ip, ?int $userId): bool
    {
        $key = "admin_hour:{$ip}:" . ($userId ?? 'guest');
        $limit = $this->limits['requests_per_hour'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 3600); // 1 hour decay
        return true;
    }

    protected function trackFailedRequest(string $ip): void
    {
        $key = "admin_failed:{$ip}";
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, now()->addMinutes(10));
        }

        if ($count >= $this->limits['failed_requests_limit']) {
            $this->recordViolation($ip, 'failed_requests');
            Cache::forget($key);
        }
    }

    protected function recordViolation(string $ip, string $type): void
    {
        $violationKey = "admin_violations:{$ip}";
        $violations = Cache::increment($violationKey);

        if ($violations === 1) {
            Cache::put($violationKey, 1, now()->addHours(24));
        }

        // Calculate block duration based on violation count
        $blockMinutes = $this->blockDurations[min($violations, 5)];
        $blockedUntil = now()->addMinutes($blockMinutes)->toDateTimeString();

        Cache::put("admin_ddos_block:{$ip}", $blockedUntil, now()->addMinutes($blockMinutes));

        // Log the violation
        Log::warning('Admin DDoS Protection: IP temporarily blocked', [
            'ip' => $ip,
            'type' => $type,
            'violations' => $violations,
            'blocked_minutes' => $blockMinutes,
        ]);

        // Auto-block permanently after 5 violations
        if ($violations >= 5) {
            try {
                BlockedIp::blockIp($ip, "Auto-blocked: Multiple DDoS violations ({$type})", 24);
                Log::alert('Admin DDoS Protection: IP permanently blocked', [
                    'ip' => $ip,
                    'violations' => $violations,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to permanently block IP: ' . $e->getMessage());
            }
        }
    }

    protected function addRateLimitHeaders(Response $response, string $ip, ?int $userId): Response
    {
        $key = "admin_minute:{$ip}:" . ($userId ?? 'guest');
        $limit = $this->limits['requests_per_minute'];
        $remaining = RateLimiter::remaining($key, $limit);

        $response->headers->set('X-RateLimit-Limit', (string) $limit);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $remaining));
        $response->headers->set('X-RateLimit-Reset', (string) RateLimiter::availableIn($key));

        return $response;
    }

    protected function rateLimitResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 429);
        }

        return response()->view('errors.429', ['message' => $message], 429);
    }

    protected function blockedResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        return response()->view('errors.403', ['message' => $message], 403);
    }
}
