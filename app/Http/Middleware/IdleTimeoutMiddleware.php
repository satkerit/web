<?php

namespace App\Http\Middleware;

use App\Models\AuditTrail;
use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // Skip for login, logout, and authentication routes
        if ($request->is('login') || $request->is('logout') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }

        // Skip for storage and download routes
        if ($request->is('storage/*') || str_contains($request->getPathInfo(), '/download/')) {
            return $next($request);
        }

        // Get security settings from database
        $settings = SecuritySetting::getSettings();
        
        // Skip if session tracking is disabled
        if (!$settings->enable_session_tracking) {
            return $next($request);
        }

        $user = Auth::user();
        $idleTimeout = $settings->idle_timeout ?: (int) config('session.idle_timeout', 15);
        $idleTimeoutSeconds = $idleTimeout * 60; // Convert to seconds
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;

        // Get last activity time
        $lastActivity = Cache::get($sessionKey, $currentTime);

        // Update last activity time on every request to keep session alive
        // This ensures proper synchronization between frontend and backend
        if ($settings->auto_extend_session) {
            $cacheDuration = now()->addMinutes($idleTimeout + 10);
            Cache::put($sessionKey, $currentTime, $cacheDuration);
            
            // Update last activity for response headers
            $lastActivity = $currentTime;
        }

        // Check if user has been idle too long (with 10 second tolerance for processing delay)
        $timeIdle = $currentTime - $lastActivity;
        if ($timeIdle > $idleTimeoutSeconds + 10) {
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
                    'redirect' => route('admin.login'),
                    'idle_timeout' => true
                ], 401);
            }

            // Redirect to login with message
            return redirect()->route('admin.login')
                ->with('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama ' . $idleTimeout . ' menit.');
        }

        $response = $next($request);

        // Add idle timeout info to response headers for JavaScript
        if (Auth::check()) {
            $response->headers->set('X-Idle-Timeout', $idleTimeout);
            $response->headers->set('X-Last-Activity', $lastActivity);
            $response->headers->set('X-Current-Time', $currentTime);
            $response->headers->set('X-Idle-Warning', $settings->idle_warning * 60);
        }

        return $response;
    }
}
