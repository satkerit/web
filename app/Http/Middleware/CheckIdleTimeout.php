<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckIdleTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for guests or API routes
        if (!Auth::check() || $request->is('api/*')) {
            return $next($request);
        }

        $idleTimeout = config('session.idle_timeout', 30) * 60; // Convert to seconds
        $lastActivity = Session::get('last_activity', time());
        $currentTime = time();

        // Check if user has been idle too long
        if (($currentTime - $lastActivity) > $idleTimeout) {
            // Log the user out
            Auth::logout();
            Session::flush();
            Session::regenerate();

            // Redirect with message
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sesi Anda telah berakhir karena tidak aktif.',
                    'redirect' => route(config('session.idle_logout_route', 'login'))
                ], 401);
            }

            return redirect()->route(config('session.idle_logout_route', 'login'))
                ->with('warning', 'Sesi Anda telah berakhir karena tidak aktif. Silakan login kembali.');
        }

        // Update last activity time
        Session::put('last_activity', $currentTime);

        return $next($request);
    }
}
