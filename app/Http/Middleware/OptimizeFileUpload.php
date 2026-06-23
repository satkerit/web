<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
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
     * Optimize PHP settings for file uploads using dynamic settings from SiteSetting
     */
    protected function optimizeUploadSettings(): void
    {
        try {
            $settings = SiteSetting::getSettings();

            // Memory limit
            $currentMemory = ini_get('memory_limit');
            $targetMemory = $settings->memory_limit ?? '512M';
            if ($this->parseMemoryLimit($currentMemory) < $this->parseMemoryLimit($targetMemory)) {
                ini_set('memory_limit', $targetMemory);
            }

            // Max execution time
            $currentTime = ini_get('max_execution_time');
            $targetTime = $settings->max_execution_time ?? 300;
            if ($currentTime < $targetTime) {
                ini_set('max_execution_time', (string)$targetTime);
            }

            // Max input time
            $currentInputTime = ini_get('max_input_time');
            $targetInputTime = $settings->max_input_time ?? 300;
            if ($currentInputTime < $targetInputTime) {
                ini_set('max_input_time', (string)$targetInputTime);
            }

            // Upload settings
            $uploadMaxFilesize = $settings->upload_max_filesize ?? '100M';
            $postMaxSize = $settings->post_max_size ?? '100M';
            $maxFileUploads = $settings->max_file_uploads ?? 20;

            ini_set('upload_max_filesize', $uploadMaxFilesize);
            ini_set('post_max_size', $postMaxSize);
            ini_set('max_file_uploads', (string)$maxFileUploads);

            // Log the settings
            if (function_exists('Log')) {
                \Illuminate\Support\Facades\Log::info('Dynamic upload settings applied', [
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'max_execution_time' => ini_get('max_execution_time'),
                    'max_input_time' => ini_get('max_input_time'),
                    'memory_limit' => ini_get('memory_limit'),
                    'max_file_uploads' => ini_get('max_file_uploads'),
                ]);
            }
        } catch (\Exception $e) {
            // Fallback to default settings if SiteSetting fails
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', '300');
            ini_set('max_input_time', '300');
            ini_set('upload_max_filesize', '100M');
            ini_set('post_max_size', '100M');
            ini_set('max_file_uploads', '20');

            if (function_exists('Log')) {
                \Illuminate\Support\Facades\Log::warning('Failed to apply dynamic upload settings, using defaults', [
                    'error' => $e->getMessage()
                ]);
            }
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
