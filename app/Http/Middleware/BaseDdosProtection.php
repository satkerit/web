<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseDdosProtection
{
    protected array $limits;
    protected array $blockDurations = [
        1 => 5,
        2 => 15,
        3 => 60,
        4 => 360,
        5 => 1440,
    ];
    protected array $excludedPaths = [
        'storage/*',
        'livewire/*',
        'build/*',
        'favicon.ico',
        'robots.txt',
        '_debugbar/*',
    ];

    abstract protected function getCachePrefix(): string;
    abstract protected function getLogPrefix(): string;
    abstract protected function getLimits(): array;

    public function __construct()
    {
        $this->limits = $this->getLimits();
    }

    protected function handleCheck(Request $request, Closure $next, ?callable $extraChecks = null): Response
    {
        if ($this->isExcludedPath($request)) {
            return $next($request);
        }

        $ip = $request->ip();

        if ($this->isIpBlocked($ip)) {
            return $this->blockedResponse($request, 'Akses ditolak.');
        }

        if ($this->isTemporarilyBlocked($ip)) {
            $remaining = $this->getBlockTimeRemaining($ip);
            $msg = "Terlalu banyak request. Coba lagi dalam {$remaining} menit.";
            return $this->rateLimitResponse($request, $msg);
        }

        if ($extraChecks) {
            $result = $extraChecks($request, $ip);
            if ($result instanceof Response) {
                return $result;
            }
        }

        if (!$this->checkBurstLimit($ip)) {
            $this->recordViolation($ip, 'burst');
            return $this->rateLimitResponse($request, 'Terlalu cepat! Mohon tunggu sebentar.');
        }

        if (!$this->checkMinuteLimit($ip)) {
            $this->recordViolation($ip, 'minute');
            return $this->rateLimitResponse($request, 'Rate limit tercapai. Coba lagi dalam 1 menit.');
        }

        if (
            isset($this->limits['requests_per_hour']) &&
            !$this->checkHourLimit($ip)
        ) {
            $this->recordViolation($ip, 'hour');
            return $this->rateLimitResponse($request, 'Batas request per jam tercapai.');
        }

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
        return Cache::has($this->blockKey($ip));
    }

    protected function getBlockTimeRemaining(string $ip): int
    {
        $blockedUntil = Cache::get($this->blockKey($ip));
        if (!$blockedUntil) return 0;
        return max(0, (int) ceil((strtotime($blockedUntil) - time()) / 60));
    }

    protected function checkBurstLimit(string $ip): bool
    {
        $key = $this->cachePrefix() . "burst:{$ip}";
        $limit = $this->limits['requests_per_second'] ?? 10;

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }
        RateLimiter::hit($key, 1);
        return true;
    }

    protected function checkMinuteLimit(string $ip): bool
    {
        $key = $this->cachePrefix() . "minute:{$ip}";
        $limit = $this->limits['requests_per_minute'] ?? 120;

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }
        RateLimiter::hit($key, 60);
        return true;
    }

    protected function checkHourLimit(string $ip): bool
    {
        $key = $this->cachePrefix() . "hour:{$ip}";
        $limit = $this->limits['requests_per_hour'] ?? 3000;

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return false;
        }
        RateLimiter::hit($key, 3600);
        return true;
    }

    protected function recordViolation(string $ip, string $type): void
    {
        $key = $this->cachePrefix() . "violations:{$ip}";
        $violations = Cache::increment($key);

        if ($violations === 1) {
            Cache::put($key, 1, now()->addHours(24));
        }

        $blockMinutes = $this->blockDurations[min($violations, 5)];
        $blockedUntil = now()->addMinutes($blockMinutes)->toDateTimeString();
        Cache::put($this->blockKey($ip), $blockedUntil, now()->addMinutes($blockMinutes));

        Log::warning($this->getLogPrefix() . ': violation recorded', [
            'ip' => $ip,
            'type' => $type,
            'violations' => $violations,
            'blocked_minutes' => $blockMinutes,
        ]);

        $permanentThreshold = $this->limits['permanent_block_threshold'] ?? 10;
        if ($violations >= $permanentThreshold) {
            try {
                BlockedIp::blockIp($ip, "Auto-blocked: {$type}", 48);
                Log::alert($this->getLogPrefix() . ': IP permanently blocked', [
                    'ip' => $ip, 'violations' => $violations,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to block IP: ' . $e->getMessage());
            }
        }
    }

    protected function rateLimitResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->ajax() || $request->hasHeader('X-Livewire')) {
            return response()->json(['success' => false, 'message' => $message], 429);
        }
        return response()->view('errors.429', ['message' => $message], 429);
    }

    protected function blockedResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson() || $request->ajax() || $request->hasHeader('X-Livewire')) {
            return response()->json(['success' => false, 'message' => $message], 403);
        }
        return response()->view('errors.403', ['message' => $message], 403);
    }

    protected function addRateLimitHeaders(Response $response, string $ip): Response
    {
        $key = $this->cachePrefix() . "minute:{$ip}";
        $limit = $this->limits['requests_per_minute'] ?? 120;
        $remaining = RateLimiter::remaining($key, $limit);

        $response->headers->set('X-RateLimit-Limit', (string) $limit);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $remaining));
        $response->headers->set('X-RateLimit-Reset', (string) RateLimiter::availableIn($key));

        return $response;
    }

    private function cachePrefix(): string
    {
        return $this->getCachePrefix();
    }

    private function blockKey(string $ip): string
    {
        return $this->cachePrefix() . "block:{$ip}";
    }
}