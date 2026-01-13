<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class DdosProtection
{
    /**
     * Rate limit configurations (loaded from env)
     */
    protected array $limits;

    /**
     * Suspicious patterns that indicate potential DDoS
     */
    protected array $suspiciousPatterns;

    /**
     * Paths to exclude from DDoS protection
     */
    protected array $excludedPaths = [
        'storage/*',
        'livewire/*',
        'build/*',
        'favicon.ico',
        'robots.txt',
        '_debugbar/*',
    ];

    public function __construct()
    {
        $this->limits = [
            'requests_per_second' => (int) env('DDOS_REQUESTS_PER_SECOND', 10),
            'requests_per_minute' => (int) env('DDOS_REQUESTS_PER_MINUTE', 120),
            'requests_per_hour' => (int) env('DDOS_REQUESTS_PER_HOUR', 3000),
        ];

        $this->suspiciousPatterns = [
            'rapid_fire' => (int) env('DDOS_RAPID_FIRE_THRESHOLD', 20),
            'same_endpoint' => (int) env('DDOS_SAME_ENDPOINT_THRESHOLD', 30),
        ];
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Skip excluded paths
        if ($this->isExcludedPath($request)) {
            return $next($request);
        }

        $ip = $request->ip();

        // Check if IP is blocked
        if ($this->isIpBlocked($ip)) {
            return $this->blockedResponse($request);
        }

        // Check if temporarily blocked
        if ($this->isTemporarilyBlocked($ip)) {
            return $this->temporaryBlockResponse($request, $ip);
        }

        // Run DDoS checks
        $checkResult = $this->runDdosChecks($request, $ip);
        
        if ($checkResult !== true) {
            return $checkResult;
        }

        // Track request for analytics
        $this->trackRequest($ip, $request);

        return $next($request);
    }

    protected function isExcludedPath(Request $request): bool
    {
        foreach ($this->excludedPaths as $pattern) {
            if ($request->is($pattern)) {
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

    protected function isTemporarilyBlocked(string $ip): bool
    {
        return Cache::has("ddos_block:{$ip}");
    }

    protected function runDdosChecks(Request $request, string $ip): Response|bool
    {
        // 1. Burst protection (requests per second)
        if (!$this->checkBurstLimit($ip)) {
            $this->recordViolation($ip, 'burst', 5);
            return $this->rateLimitResponse($request, 'Terlalu banyak request. Mohon tunggu sebentar.');
        }

        // 2. Per-minute rate limit
        if (!$this->checkMinuteLimit($ip)) {
            $this->recordViolation($ip, 'minute', 10);
            return $this->rateLimitResponse($request, 'Rate limit tercapai. Coba lagi dalam 1 menit.');
        }

        // 3. Per-hour rate limit
        if (!$this->checkHourLimit($ip)) {
            $this->recordViolation($ip, 'hour', 30);
            return $this->rateLimitResponse($request, 'Batas request per jam tercapai.');
        }

        // 4. Rapid fire detection (potential bot/DDoS)
        if ($this->detectRapidFire($ip)) {
            $this->recordViolation($ip, 'rapid_fire', 15);
            return $this->rateLimitResponse($request, 'Aktivitas mencurigakan terdeteksi.');
        }

        // 5. Same endpoint abuse detection
        if ($this->detectEndpointAbuse($ip, $request->path())) {
            $this->recordViolation($ip, 'endpoint_abuse', 10);
            return $this->rateLimitResponse($request, 'Terlalu banyak request ke halaman yang sama.');
        }

        return true;
    }

    protected function checkBurstLimit(string $ip): bool
    {
        $key = "ddos_burst:{$ip}";
        $limit = $this->limits['requests_per_second'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 1);
        return true;
    }

    protected function checkMinuteLimit(string $ip): bool
    {
        $key = "ddos_minute:{$ip}";
        $limit = $this->limits['requests_per_minute'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 60);
        return true;
    }

    protected function checkHourLimit(string $ip): bool
    {
        $key = "ddos_hour:{$ip}";
        $limit = $this->limits['requests_per_hour'];

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }

        RateLimiter::hit($key, 3600);
        return true;
    }

    protected function detectRapidFire(string $ip): bool
    {
        $key = "ddos_rapid:{$ip}";
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, 5); // 5 second window
        }

        return $count > $this->suspiciousPatterns['rapid_fire'];
    }

    protected function detectEndpointAbuse(string $ip, string $path): bool
    {
        $key = "ddos_endpoint:{$ip}:" . md5($path);
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, 60); // 1 minute window
        }

        return $count > $this->suspiciousPatterns['same_endpoint'];
    }

    protected function recordViolation(string $ip, string $type, int $blockMinutes): void
    {
        $violationKey = "ddos_violations:{$ip}";
        $violations = Cache::increment($violationKey);

        if ($violations === 1) {
            Cache::put($violationKey, 1, now()->addHours(24));
        }

        // Progressive blocking
        $actualBlockMinutes = $blockMinutes * min($violations, 5);
        
        Cache::put("ddos_block:{$ip}", now()->addMinutes($actualBlockMinutes)->toDateTimeString(), now()->addMinutes($actualBlockMinutes));

        Log::warning('DDoS Protection: Violation recorded', [
            'ip' => $ip,
            'type' => $type,
            'violations' => $violations,
            'block_minutes' => $actualBlockMinutes,
        ]);

        // Auto permanent block after many violations
        if ($violations >= 10) {
            try {
                BlockedIp::blockIp($ip, "Auto-blocked: DDoS attack detected ({$type})", 48);
                Log::alert('DDoS Protection: IP permanently blocked', [
                    'ip' => $ip,
                    'violations' => $violations,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to block IP: ' . $e->getMessage());
            }
        }
    }

    protected function trackRequest(string $ip, Request $request): void
    {
        // Track for analytics (lightweight)
        $key = 'ddos_stats_' . date('Y-m-d-H');
        Cache::increment($key);
        Cache::put($key, Cache::get($key, 0), now()->addHours(25));
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

    protected function blockedResponse(Request $request): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        return response()->view('errors.403', ['message' => 'IP Anda telah diblokir karena aktivitas mencurigakan.'], 403);
    }

    protected function temporaryBlockResponse(Request $request, string $ip): Response
    {
        $blockedUntil = Cache::get("ddos_block:{$ip}");
        $remaining = $blockedUntil ? max(0, ceil((strtotime($blockedUntil) - time()) / 60)) : 0;

        $message = "Terlalu banyak request. Coba lagi dalam {$remaining} menit.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'retry_after' => $remaining * 60,
            ], 429);
        }

        return response()->view('errors.429', ['message' => $message], 429);
    }
}
