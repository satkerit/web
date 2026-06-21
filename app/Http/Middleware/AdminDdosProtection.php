<?php

namespace App\Http\Middleware;

use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AdminDdosProtection extends BaseDdosProtection
{
    protected array $blockDurations = [
        1 => 5,
        2 => 15,
        3 => 60,
        4 => 360,
        5 => 1440,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->loadSettingsLimits();
    }

    protected function getCachePrefix(): string
    {
        return 'admin_ddos_';
    }

    protected function getLogPrefix(): string
    {
        return 'Admin DDoS Protection';
    }

    protected function getLimits(): array
    {
        return [
            'requests_per_minute' => 60,
            'requests_per_second' => 5,
            'requests_per_hour' => 1000,
            'failed_requests_limit' => 10,
            'permanent_block_threshold' => 5,
        ];
    }

    public function handle(Request $request, Closure $next): Response
    {
        $isFileUpload = $this->isFileUploadRequest($request);

        if ($isFileUpload) {
            $this->limits['requests_per_second'] = 2;
            $this->limits['requests_per_minute'] = 20;
        }

        $response = $this->handleCheck($request, $next);

        if ($isFileUpload && $response->getStatusCode() === 429) {
            $response = $this->rateLimitResponse(
                $request,
                'Upload terlalu cepat! Mohon tunggu sebentar sebelum upload lagi.'
            );
        }

        try {
            if ($response->getStatusCode() >= 400) {
                if (!($isFileUpload && $response->getStatusCode() === 503)) {
                    $this->trackFailedRequest($request->ip());
                }
            }
        } catch (\Throwable $e) {
            if (!$isFileUpload) {
                $this->trackFailedRequest($request->ip());
            }
        }

        return $this->addRateLimitHeaders($response, $request->ip());
    }

    protected function isFileUploadRequest(Request $request): bool
    {
        if ($request->hasFile('featured_image') || $request->hasFile('slide_images')) {
            return true;
        }

        $contentType = $request->header('Content-Type', '');
        if (str_contains($contentType, 'multipart/form-data')) {
            return true;
        }

        $route = $request->route();
        if ($route) {
            $routeName = $route->getName();
            $routeUri = $route->uri();

            if (
                str_contains($routeName, 'store') ||
                str_contains($routeName, 'update') ||
                str_contains($routeUri, 'upload') ||
                str_contains($routeUri, 'image')
            ) {
                return true;
            }
        }

        return false;
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

    protected function trackFailedRequest(string $ip): void
    {
        $key = $this->getCachePrefix() . "failed:{$ip}";
        $count = Cache::increment($key);

        if ($count === 1) {
            Cache::put($key, 1, now()->addMinutes(10));
        }

        if ($count >= ($this->limits['failed_requests_limit'] ?? 10)) {
            $this->recordViolation($ip, 'failed_requests');
            Cache::forget($key);
        }
    }
}