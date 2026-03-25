<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UploadProgressHelper
{
    /**
     * Track upload progress
     */
    public static function trackProgress(string $sessionId, int $current, int $total, string $status = 'processing'): void
    {
        $progress = [
            'current' => $current,
            'total' => $total,
            'percentage' => $total > 0 ? round(($current / $total) * 100, 1) : 0,
            'status' => $status,
            'timestamp' => now()->toISOString(),
        ];

        Cache::put("upload_progress:{$sessionId}", $progress, now()->addMinutes(10));
    }

    /**
     * Get upload progress
     */
    public static function getProgress(string $sessionId): ?array
    {
        return Cache::get("upload_progress:{$sessionId}");
    }

    /**
     * Mark upload as completed
     */
    public static function markCompleted(string $sessionId, array $results = []): void
    {
        $progress = [
            'status' => 'completed',
            'results' => $results,
            'timestamp' => now()->toISOString(),
        ];

        Cache::put("upload_progress:{$sessionId}", $progress, now()->addMinutes(5));
    }

    /**
     * Mark upload as failed
     */
    public static function markFailed(string $sessionId, string $error): void
    {
        $progress = [
            'status' => 'failed',
            'error' => $error,
            'timestamp' => now()->toISOString(),
        ];

        Cache::put("upload_progress:{$sessionId}", $progress, now()->addMinutes(5));
        
        Log::error('Upload failed', [
            'session_id' => $sessionId,
            'error' => $error
        ]);
    }

    /**
     * Clear progress data
     */
    public static function clearProgress(string $sessionId): void
    {
        Cache::forget("upload_progress:{$sessionId}");
    }
}