<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

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
            abort(404);
        }

        // Cek file exists
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);
        
        // Pastikan ini file, bukan directory
        if (is_dir($fullPath)) {
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
