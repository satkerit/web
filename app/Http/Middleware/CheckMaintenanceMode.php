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

        $settings = SiteSetting::getSettings();
        $clientIp = $request->ip();
        $path = $request->path();

        // Allow whitelisted IPs for all maintenance checks
        if ($settings->isIpAllowed($clientIp)) {
            return $next($request);
        }

        // Check global maintenance mode first
        if (SiteSetting::isMaintenanceMode()) {
            return response()->view('errors.503', [
                'message' => $settings->maintenance_message,
                'endTime' => $settings->maintenance_end_time,
            ], 503);
        }

        // Check partial/page-specific maintenance
        if (SiteSetting::isPageUnderMaintenance($path)) {
            $pageKey = SiteSetting::getPageKeyFromPath($path);
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
}
