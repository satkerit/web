<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeFileUpload
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a file upload request
        if ($this->isFileUploadRequest($request)) {
            // Optimize PHP settings for file uploads
            $this->optimizeUploadSettings();
        }

        return $next($request);
    }

    /**
     * Check if the request contains file uploads
     */
    protected function isFileUploadRequest(Request $request): bool
    {
        // Check for common file upload fields
        $fileFields = ['featured_image', 'slide_images', 'image', 'file', 'attachment'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                return true;
            }
        }

        // Check content type
        $contentType = $request->header('Content-Type', '');
        return str_contains($contentType, 'multipart/form-data');
    }

    /**
     * Optimize PHP settings for file uploads
     */
    protected function optimizeUploadSettings(): void
    {
        // Increase memory limit for image processing
        $currentMemory = ini_get('memory_limit');
        if ($this->parseMemoryLimit($currentMemory) < $this->parseMemoryLimit('512M')) {
            ini_set('memory_limit', '512M');
        }

        // Increase execution time for uploads
        $currentTime = ini_get('max_execution_time');
        if ($currentTime < 300) {
            ini_set('max_execution_time', '300'); // 5 minutes
        }

        // Optimize upload settings
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M'); // Allow multiple files
        ini_set('max_file_uploads', '20');
        
        // Log the settings
        if (function_exists('Log')) {
            \Illuminate\Support\Facades\Log::info('Upload settings applied', [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
            ]);
        }

        // Optimize for image processing
        ini_set('gd.jpeg_ignore_warning', '1');
    }

    /**
     * Parse memory limit string to bytes
     */
    protected function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }
}
