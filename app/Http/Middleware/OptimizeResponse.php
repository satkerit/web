<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Skip optimization for download routes and binary files
        if ($this->shouldSkipOptimization($request, $response)) {
            return $response;
        }

        // Only optimize HTML responses
        if ($this->isHtmlResponse($response)) {
            $content = $response->getContent();

            // Placeholders for content that shouldn't be minified
            $placeholders = [];
            $content = preg_replace_callback('/<(script|style|pre|textarea)[^>]*>.*?<\/\1>/is', function ($matches) use (&$placeholders) {
                $placeholder = '###PHP_OPTIMIZE_PLACEHOLDER_' . count($placeholders) . '###';
                $placeholders[$placeholder] = $matches[0];
                return $placeholder;
            }, $content);

            // Remove HTML comments (except IE conditionals)
            $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

            // Remove extra whitespace between tags
            $content = preg_replace('/>\s+</', '><', $content);

            // Remove multiple spaces/newlines but keep single newline if needed
            // However, since we've protected scripts/styles, we can be more aggressive with the rest
            $content = preg_replace('/\s{2,}/', ' ', $content);

            // Restore placeholders
            if (!empty($placeholders)) {
                $content = strtr($content, $placeholders);
            }

            $response->setContent($content);
        }

        return $response;
    }

    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html') || empty($contentType);
    }

    protected function shouldSkipOptimization(Request $request, Response $response): bool
    {
        // Skip for download routes
        if (str_contains($request->getPathInfo(), '/download/')) {
            return true;
        }

        // Skip for binary content types
        $contentType = $response->headers->get('Content-Type', '');
        $binaryTypes = [
            'application/octet-stream',
            'application/sql',
            'application/gzip',
            'application/x-gzip',
            'application/zip',
            'application/x-sql'
        ];

        foreach ($binaryTypes as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }

        // Skip for file downloads (Content-Disposition: attachment)
        $disposition = $response->headers->get('Content-Disposition', '');
        if (str_contains($disposition, 'attachment')) {
            return true;
        }

        return false;
    }
}
