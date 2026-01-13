<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk serve file dari storage
 * Solusi untuk shared hosting dimana storage berada di luar public_html
 */
class StorageServeController extends Controller
{
    public function serve(string $path)
    {
        // Sanitize path untuk keamanan - cegah directory traversal
        $path = str_replace(['..', '\\'], ['', '/'], $path);
        $path = preg_replace('/\/+/', '/', $path);
        $path = trim($path, '/');
        
        // Validasi path tidak kosong
        if (empty($path)) {
            Log::warning('StorageServe: Empty path requested');
            abort(404);
        }

        // Cek file exists
        if (!Storage::disk('public')->exists($path)) {
            Log::warning('StorageServe: File not found', [
                'path' => $path,
                'full_path' => Storage::disk('public')->path($path),
                'storage_path' => storage_path('app/public'),
            ]);
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);
        
        // Pastikan ini file, bukan directory
        if (is_dir($fullPath)) {
            Log::warning('StorageServe: Path is directory', ['path' => $path]);
            abort(404);
        }

        $mimeType = Storage::disk('public')->mimeType($path) ?? 'application/octet-stream';
        $lastModified = Storage::disk('public')->lastModified($path);
        $fileSize = Storage::disk('public')->size($path);

        // Serve file dengan header caching
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
            'ETag' => '"' . md5($lastModified . $fileSize) . '"',
        ]);
    }
}
