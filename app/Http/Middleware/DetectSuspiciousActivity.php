<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\SecurityLog;
use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectSuspiciousActivity
{
    protected array $sqlInjectionPatterns;
    protected array $xssPatterns;
    protected array $pathTraversalPatterns;
    protected array $commandInjectionPatterns;
    protected array $fileInclusionPatterns;
    protected array $scannerAgents;
    protected array $excludedRoutes = [
        '_ignition/*', 'sanctum/*', 'telescope/*', '__clockwork/*', 'logout',
        'admin/company-info*', 'admin/news*', 'admin/products*', 'admin/hero-slides*',
        'admin/why-choose-us*', 'admin/board-members*', 'admin/offices*', 'admin/careers*',
        'admin/brochures*', 'admin/auctions*', 'admin/reports*', 'admin/storage*'
    ];

    public function __construct()
    {
        $config = config('security-patterns');
        $this->sqlInjectionPatterns = $config['sql_injection'] ?? [];
        $this->xssPatterns = $config['xss'] ?? [];
        $this->pathTraversalPatterns = $config['path_traversal'] ?? [];
        $this->commandInjectionPatterns = $config['command_injection'] ?? [];
        $this->fileInclusionPatterns = $config['file_inclusion'] ?? [];
        $this->scannerAgents = $config['scanner_agents'] ?? [];
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isExcludedRoute($request)) {
            return $next($request);
        }

        $ip = $request->ip();

        if ($this->isIpBlocked($ip)) {
            SecurityLog::logThreat($ip, 'blocked_ip', $request->fullUrl(), null, null, 'high', true);
            return $this->blockedResponse($request);
        }

        $this->loadSettings();

        $inputsToCheck = $this->collectInputs($request);
        $threat = $this->detectThreat($inputsToCheck, $request);

        if ($threat) {
            return $this->handleThreat($request, $ip, $threat);
        }

        if ($this->isSuspiciousAgent($request)) {
            return $this->handleThreat($request, $ip, [
                'type' => 'scanner',
                'pattern' => 'Suspicious User Agent',
                'input' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }

    protected function collectInputs(Request $request): array
    {
        $inputs = ['url_path' => urldecode($request->path())];

        if ($queryString = $request->getQueryString()) {
            $inputs['query_string'] = urldecode($queryString);
        }

        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                $inputs["input_{$key}"] = $value;
            } elseif (is_array($value)) {
                $inputs["input_{$key}"] = json_encode($value);
            }
        }

        return array_filter($inputs);
    }

    protected function detectThreat(array $inputs, Request $request): ?array
    {
        $categories = [
            'sql_injection' => $this->sqlInjectionPatterns,
            'xss' => $this->xssPatterns,
            'path_traversal' => $this->pathTraversalPatterns,
            'command_injection' => $this->commandInjectionPatterns,
            'file_inclusion' => $this->fileInclusionPatterns,
        ];

        foreach ($inputs as $source => $value) {
            if (empty($value) || !is_string($value)) continue;

            foreach ($categories as $type => $patterns) {
                foreach ($patterns as $pattern) {
                    if (@preg_match($pattern, $value)) {
                        return ['type' => $type, 'pattern' => $pattern, 'input' => $value, 'source' => $source];
                    }
                }
            }
        }

        return null;
    }

    protected function isSuspiciousAgent(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');
        if (empty($userAgent)) return false;

        foreach ($this->scannerAgents as $agent) {
            if (str_contains($userAgent, strtolower($agent))) return true;
        }

        return false;
    }

    protected function handleThreat(Request $request, string $ip, array $threat): Response
    {
        $settings = $this->loadSettings();

        $violationKey = "threat_count:{$ip}";
        $count = Cache::increment($violationKey);
        if ($count === 1) {
            Cache::put($violationKey, 1, now()->addHour());
        }

        SecurityLog::logThreat(
            $ip,
            $threat['type'],
            $request->fullUrl(),
            $threat['input'],
            $threat['pattern'],
            $count >= 3 ? 'high' : 'medium',
            $count >= $this->blockThreshold
        );

        if ($settings && $settings->log_security_events) {
            Log::warning('Suspicious activity detected', [
                'ip' => $ip,
                'type' => $threat['type'],
                'path' => $request->path(),
                'user_agent' => $request->userAgent(),
                'count' => $count,
            ]);
        }

        if ($count >= $this->blockThreshold) {
            BlockedIp::blockIp($ip, "Auto-blocked: {$threat['type']}", $this->blockDurationHours);
        }

        return $this->blockedResponse($request);
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

    protected int $blockThreshold = 5;
    protected int $blockDurationHours = 24;

    protected function loadSettings(): ?SecuritySetting
    {
        try {
            $settings = Cache::remember('security_settings', 300, fn() => SecuritySetting::first());

            if ($settings) {
                $this->blockThreshold = $settings->block_threshold ?? 5;
                $this->blockDurationHours = $settings->block_duration_hours ?? 24;
            }

            return $settings;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function blockedResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Access Denied'], 403);
        }
        return response()->view('errors.403', [], 403);
    }
}