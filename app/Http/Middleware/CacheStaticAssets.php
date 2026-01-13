<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheStaticAssets
{
    /**
     * Handle an incoming request.
     * Add cache headers for static assets to improve performance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add cache headers for successful responses
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $path = $request->path();

        // Cache images for 1 year (immutable)
        if (preg_match('/\.(jpg|jpeg|png|gif|webp|svg|ico)$/i', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            return $response;
        }

        // Cache CSS/JS for 1 year (versioned by Vite)
        if (preg_match('/\.(css|js)$/i', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            return $response;
        }

        // Cache fonts for 1 year
        if (preg_match('/\.(woff|woff2|ttf|eot|otf)$/i', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            return $response;
        }

        return $response;
    }
}
