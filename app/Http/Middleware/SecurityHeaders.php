<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers to add to all responses
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate CSP nonce early so it's available in Blade views
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        // Tell Vite to use this nonce
        if (class_exists(\Illuminate\Support\Facades\Vite::class)) {
            \Illuminate\Support\Facades\Vite::useCspNonce($nonce);
        }

        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy - Allow geolocation for prayer times widget
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=(), camera=()');

        // Content Security Policy
        $csp = $this->buildContentSecurityPolicy();
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS for production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Add additional security headers
        $this->addAdditionalSecurityHeaders($response);

        return $response;
    }

    /**
     * Build Content Security Policy header
     */
    protected function buildContentSecurityPolicy(): string
    {
        $nonce = request()->attributes->get('csp_nonce');

        $policies = [
            "default-src 'self'",
            // Scripts: Allow self, nonce-protected inline, and trusted CDNs
            "script-src 'self' 'nonce-{$nonce}' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com",
            // Styles: Allow self, and unsafe-inline for Alpine.js/dynamic styles
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://unpkg.com https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://cdn.jsdelivr.net https://unpkg.com https://tile.openstreetmap.org https://a.tile.openstreetmap.org https://b.tile.openstreetmap.org https://c.tile.openstreetmap.org https://nominatim.openstreetmap.org http://api.aladhan.com",
            "frame-src 'self' https://www.google.com https://maps.google.com",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'",
            "object-src 'none'",
        ];

        // Only add upgrade-insecure-requests in production with HTTPS
        if (app()->environment('production') && request()->secure()) {
            $policies[] = "upgrade-insecure-requests";
        }

        // Add report-uri for monitoring (optional)
        if (config('security.csp.report_violations', false)) {
            $policies[] = "report-uri /api/csp-report";
        }

        return implode('; ', $policies);
    }

    /**
     * Add additional security headers
     */
    protected function addAdditionalSecurityHeaders(Response $response): void
    {
        // Cross-Origin-Opener-Policy (COOP)
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        // Cross-Origin-Embedder-Policy (COEP)
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        // Cross-Origin-Resource-Policy (CORP)
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        // X-Permitted-Cross-Domain-Policies
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Expect-CT (Certificate Transparency)
        $response->headers->set('Expect-CT', 'max-age=86400, enforce');

        // Remove server information
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
    }
}
