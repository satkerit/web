<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers to add to all responses
     */
    public function handle(Request $request, Closure $next): Response
    {
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
     * 
     * SECURITY NOTE: Using 'unsafe-inline' for compatibility with existing inline styles.
     * Nonce is generated but 'unsafe-inline' takes precedence for backward compatibility.
     */
    protected function buildContentSecurityPolicy(): string
    {
        $nonce = base64_encode(random_bytes(16));
        request()->attributes->set('csp_nonce', $nonce);

        $policies = [
            "default-src 'self'",
            // Scripts: Allow inline for compatibility
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com",
            // Styles: Allow inline for compatibility (nonce removed to allow unsafe-inline)
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
        // Use 'unsafe-none' for compatibility with external resources
        // Change to 'require-corp' in production after testing
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
        
        // Cross-Origin-Resource-Policy (CORP)
        // Use 'cross-origin' for compatibility with CDN resources
        $response->headers->set('Cross-Origin-Resource-Policy', 'cross-origin');
        
        // Expect-CT (Certificate Transparency)
        if (app()->environment('production')) {
            $response->headers->set('Expect-CT', 'max-age=86400, enforce');
        }
        
        // Remove server information
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
    }
}
