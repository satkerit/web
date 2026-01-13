<?php

namespace App\Http\Middleware;

use App\Models\AuditTrail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for guest users
        if (!Auth::check()) {
            return $next($request);
        }

        // Skip for storage and download routes
        if ($request->is('storage/*') || str_contains($request->getPathInfo(), '/download/')) {
            return $next($request);
        }

        $user = Auth::user();
        $idleTimeout = Config::get('security.idle_timeout', 30) * 60; // Convert to seconds
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;

        // Get last activity time
        $lastActivity = Cache::get($sessionKey, $currentTime);

        // Check if user has been idle too long
        if (($currentTime - $lastActivity) > $idleTimeout) {
            // Log idle logout
            AuditTrail::log('idle_logout', 'User logged out due to inactivity: ' . $user->name);

            // Logout user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi Anda telah berakhir karena tidak ada aktivitas.',
                    'redirect' => route(Config::get('session.idle_logout_route', 'login')),
                    'idle_timeout' => true
                ], 401);
            }

            // Redirect to login with message
            return redirect()->route(Config::get('session.idle_logout_route', 'login'))
                ->with('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama ' . Config::get('security.idle_timeout', 30) . ' menit.');
        }

        // Update last activity time for non-AJAX requests or if auto-extend is enabled
        if (Config::get('session.auto_extend', true) && (!$request->ajax() && !$request->expectsJson())) {
            Cache::put($sessionKey, $currentTime, now()->addMinutes(Config::get('security.idle_timeout', 30) + 10));
        }

        $response = $next($request);

        // Add idle timeout info to response headers for JavaScript
        if (Auth::check()) {
            $response->headers->set('X-Idle-Timeout', $idleTimeout);
            $response->headers->set('X-Last-Activity', $lastActivity);
            $response->headers->set('X-Current-Time', $currentTime);
            $response->headers->set('X-Idle-Warning', Config::get('session.idle_warning', 5) * 60);
        }

        return $response;
    }
}
