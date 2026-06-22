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
        // Always optimize upload settings, not just for file upload requests
        // This ensures settings are applied before request parsing
        $this->optimizeUploadSettings();

        return $next($request);
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

        // Increase max input time
        $currentInputTime = ini_get('max_input_time');
        if ($currentInputTime < 300) {
            ini_set('max_input_time', '300');
        }

        // Optimize upload settings - set very high to avoid 413
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M'); // Allow multiple files
        ini_set('max_file_uploads', '20');

        // Log the settings
        if (function_exists('Log')) {
            \Illuminate\Support\Facades\Log::info('Upload settings applied', [
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'max_execution_time' => ini_get('max_execution_time'),
                'max_input_time' => ini_get('max_input_time'),
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
