<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SecureSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Implements additional session security measures:
     * - Session fixation protection
     * - IP address validation
     * - User agent validation
     * - Session hijacking detection
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for guest users
        if (!auth()->check()) {
            return $next($request);
        }

        // Initialize session security data
        if (!Session::has('security_fingerprint')) {
            $this->initializeSessionSecurity($request);
        }

        // Validate session security
        if (!$this->validateSessionSecurity($request)) {
            $this->handleSessionViolation($request);
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir karena alasan keamanan. Silakan login kembali.');
        }

        // Regenerate session ID periodically (every 30 minutes)
        if ($this->shouldRegenerateSession()) {
            $request->session()->regenerate();
            Session::put('last_regeneration', now()->timestamp);
        }

        return $next($request);
    }

    /**
     * Initialize session security fingerprint
     */
    protected function initializeSessionSecurity(Request $request): void
    {
        Session::put('security_fingerprint', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now()->timestamp,
            'last_activity' => now()->timestamp,
            'last_regeneration' => now()->timestamp,
        ]);
    }

    /**
     * Validate session security
     */
    protected function validateSessionSecurity(Request $request): bool
    {
        $fingerprint = Session::get('security_fingerprint');

        if (!$fingerprint) {
            return false;
        }

        // Validate IP address (strict mode)
        if (config('session.strict_ip_check', true)) {
            if ($fingerprint['ip'] !== $request->ip()) {
                \Log::warning('Session hijacking attempt detected: IP mismatch', [
                    'user_id' => auth()->id(),
                    'original_ip' => $fingerprint['ip'],
                    'current_ip' => $request->ip(),
                ]);
                return false;
            }
        }

        // Validate User Agent (detect session hijacking)
        if ($fingerprint['user_agent'] !== $request->userAgent()) {
            \Log::warning('Session hijacking attempt detected: User Agent mismatch', [
                'user_id' => auth()->id(),
                'original_ua' => $fingerprint['user_agent'],
                'current_ua' => $request->userAgent(),
            ]);
            return false;
        }

        // Update last activity
        Session::put('security_fingerprint.last_activity', now()->timestamp);

        return true;
    }

    /**
     * Check if session should be regenerated
     */
    protected function shouldRegenerateSession(): bool
    {
        $fingerprint = Session::get('security_fingerprint');
        
        if (!$fingerprint || !isset($fingerprint['last_regeneration'])) {
            return true;
        }

        // Regenerate every 30 minutes
        $regenerationInterval = 30 * 60; // 30 minutes in seconds
        return (now()->timestamp - $fingerprint['last_regeneration']) > $regenerationInterval;
    }

    /**
     * Handle session security violation
     */
    protected function handleSessionViolation(Request $request): void
    {
        // Log the violation
        \Log::alert('Session security violation', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
        ]);

        // Logout the user
        auth()->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();
    }
}
