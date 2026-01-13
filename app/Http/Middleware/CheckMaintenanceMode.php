<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for admin routes and storage
        if ($request->is('admin/*') || $request->is('admin') || $request->is('login') || $request->is('logout') || $request->is('storage/*')) {
            return $next($request);
        }

        // Skip for livewire requests
        if ($request->is('livewire/*')) {
            return $next($request);
        }

        // Get fresh settings from database (bypass cache untuk memastikan data terbaru)
        $settings = SiteSetting::first();
        
        // Jika tidak ada settings, lewati maintenance check
        if (!$settings) {
            return $next($request);
        }

        $clientIp = $request->ip();
        $path = $request->path();

        // Allow whitelisted IPs for all maintenance checks
        if ($settings->isIpAllowed($clientIp)) {
            return $next($request);
        }

        // Check global maintenance mode first
        if ($settings->maintenance_mode) {
            // Check if maintenance end time has passed
            if ($settings->maintenance_end_time && $settings->maintenance_end_time->isPast()) {
                $settings->update(['maintenance_mode' => false]);
                SiteSetting::clearCache();
            } else {
                return response()->view('errors.503', [
                    'message' => $settings->maintenance_message,
                    'endTime' => $settings->maintenance_end_time,
                ], 503);
            }
        }

        // Check partial/page-specific maintenance
        $maintenancePages = $settings->maintenance_pages ?? [];
        if (!empty($maintenancePages) && $this->isPathUnderMaintenance($path, $maintenancePages)) {
            $pageKey = $this->getPageKeyFromPath($path, $maintenancePages);
            $message = $pageKey
                ? $settings->getPageMaintenanceMessage($pageKey)
                : 'Halaman ini sedang dalam pemeliharaan.';

            return response()->view('errors.503', [
                'message' => $message,
                'endTime' => null,
                'isPartial' => true,
            ], 503);
        }

        return $next($request);
    }

    /**
     * Check if path is under maintenance
     */
    protected function isPathUnderMaintenance(string $path, array $maintenancePages): bool
    {
        $availablePages = SiteSetting::getAvailablePages();

        foreach ($maintenancePages as $pageKey) {
            if (!isset($availablePages[$pageKey])) {
                continue;
            }

            $page = $availablePages[$pageKey];
            $pattern = $page['pattern'];

            // Exact match for home
            if ($pattern === '/' && ($path === '/' || $path === '')) {
                return true;
            }

            // Pattern match with wildcard
            if (str_ends_with($pattern, '*')) {
                $prefix = rtrim($pattern, '/*');
                if (str_starts_with(ltrim($path, '/'), $prefix)) {
                    return true;
                }
            } else {
                // Exact match
                $cleanPath = ltrim($path, '/');
                if ($cleanPath === $pattern) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get page key from path
     */
    protected function getPageKeyFromPath(string $path, array $maintenancePages): ?string
    {
        $availablePages = SiteSetting::getAvailablePages();

        foreach ($maintenancePages as $pageKey) {
            if (!isset($availablePages[$pageKey])) {
                continue;
            }

            $page = $availablePages[$pageKey];
            $pattern = $page['pattern'];

            if ($pattern === '/' && ($path === '/' || $path === '')) {
                return $pageKey;
            }

            if (str_ends_with($pattern, '*')) {
                $prefix = rtrim($pattern, '/*');
                if (str_starts_with(ltrim($path, '/'), $prefix)) {
                    return $pageKey;
                }
            } else {
                $cleanPath = ltrim($path, '/');
                if ($cleanPath === $pattern) {
                    return $pageKey;
                }
            }
        }

        return null;
    }
}
