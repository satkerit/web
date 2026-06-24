<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DdosProtection extends BaseDdosProtection
{
    protected array $excludedPaths = [
        'storage/*',
        'livewire/*',
        'build/*',
        'favicon.ico',
        'robots.txt',
        '_debugbar/*',
        'login',
        'logout',
    ];

    protected array $suspiciousPatterns;

    public function __construct()
    {
        parent::__construct();
        $this->suspiciousPatterns = [
            'rapid_fire' => config('security.ddos.rapid_fire_threshold', 20),
            'same_endpoint' => config('security.ddos.same_endpoint_threshold', 30),
        ];
    }

    protected function getCachePrefix(): string
    {
        return 'ddos_';
    }

    protected function getLogPrefix(): string
    {
        return 'DDoS Protection';
    }

    protected function getLimits(): array
    {
        return [
            'requests_per_second' => config('security.ddos.requests_per_second', 10),
            'requests_per_minute' => config('security.ddos.requests_per_minute', 120),
            'requests_per_hour' => config('security.ddos.requests_per_hour', 3000),
            'permanent_block_threshold' => 10,
        ];
    }

    public function handle(Request $request, Closure $next): Response
    {
        return $this->handleCheck($request, $next, function ($request, $ip) {
            if ($this->detectRapidFire($ip)) {
                $this->recordViolation($ip, 'rapid_fire');
                return $this->rateLimitResponse($request, 'Aktivitas mencurigakan terdeteksi.');
            }

            if ($this->detectEndpointAbuse($ip, $request->path())) {
                $this->recordViolation($ip, 'endpoint_abuse');
                return $this->rateLimitResponse($request, 'Terlalu banyak request ke halaman yang sama.');
            }

            return null;
        });
    }

    protected function detectRapidFire(string $ip): bool
    {
        $key = $this->getCachePrefix() . "rapid:{$ip}";
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, 5);
        }

        return $count > $this->suspiciousPatterns['rapid_fire'];
    }

    protected function detectEndpointAbuse(string $ip, string $path): bool
    {
        $key = $this->getCachePrefix() . "endpoint:{$ip}:" . md5($path);
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, 60);
        }

        return $count > $this->suspiciousPatterns['same_endpoint'];
    }
}