<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogoDownloadController extends Controller
{
    /**
     * Download logo dalam format tertentu
     */
    public function download(Request $request, string $format)
    {
        // Validasi format
        $allowedFormats = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
        
        if (!in_array(strtolower($format), $allowedFormats)) {
            abort(404, 'Format tidak valid.');
        }

        // Ambil info perusahaan
        $company = CompanyInfo::first();
        
        if (!$company || !$company->logo) {
            abort(404, 'Logo tidak tersedia.');
        }

        $logoPath = $company->logo;
        
        // Cek apakah file ada
        if (!Storage::disk('public')->exists($logoPath)) {
            abort(404, 'File logo tidak ditemukan.');
        }

        $fullPath = Storage::disk('public')->path($logoPath);
        $originalExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        
        // Jika format yang diminta sama dengan format asli
        if ($format === $originalExtension) {
            return $this->downloadFile($fullPath, $format, $company->name ?? 'BPRS');
        }

        // Jika perlu konversi format (hanya untuk image)
        if (in_array($originalExtension, ['png', 'jpg', 'jpeg', 'webp']) && 
            in_array($format, ['png', 'jpg', 'jpeg', 'webp'])) {
            return $this->downloadConvertedImage($fullPath, $format, $company->name ?? 'BPRS');
        }

        // Jika tidak bisa konversi, download format asli
        return $this->downloadFile($fullPath, $originalExtension, $company->name ?? 'BPRS');
    }

    /**
     * Download file langsung
     */
    protected function downloadFile(string $path, string $format, string $companyName): Response
    {
        $filename = $this->sanitizeFilename($companyName) . '-logo.' . $format;
        
        return response()->download($path, $filename, [
            'Content-Type' => $this->getMimeType($format),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Download dengan konversi format menggunakan GD
     */
    protected function downloadConvertedImage(string $path, string $targetFormat, string $companyName): Response
    {
        if (!extension_loaded('gd')) {
            // Fallback ke download original jika GD tidak tersedia
            return $this->downloadFile($path, pathinfo($path, PATHINFO_EXTENSION), $companyName);
        }

        $sourceExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        // Load image berdasarkan format sumber
        $image = match ($sourceExtension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            default => null,
        };

        if (!$image) {
            return $this->downloadFile($path, $sourceExtension, $companyName);
        }

        // Preserve transparency untuk PNG
        if ($targetFormat === 'png') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        // Output ke buffer
        ob_start();
        
        match ($targetFormat) {
            'jpg', 'jpeg' => imagejpeg($image, null, 95),
            'png' => imagepng($image, null, 9),
            'webp' => imagewebp($image, null, 95),
            default => imagepng($image),
        };

        $content = ob_get_clean();
        imagedestroy($image);

        $filename = $this->sanitizeFilename($companyName) . '-logo.' . $targetFormat;

        return response($content, 200, [
            'Content-Type' => $this->getMimeType($targetFormat),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($content),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Get MIME type untuk format
     */
    protected function getMimeType(string $format): string
    {
        return match (strtolower($format)) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    /**
     * Sanitize filename
     */
    protected function sanitizeFilename(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $name);
        $name = preg_replace('/-+/', '-', $name);
        return trim($name, '-');
    }

    /**
     * Halaman download logo
     */
    public function index()
    {
        $company = CompanyInfo::first();
        $logoAvailable = $company && $company->logo && Storage::disk('public')->exists($company->logo);
        $logoExtension = $logoAvailable ? strtolower(pathinfo($company->logo, PATHINFO_EXTENSION)) : null;

        return view('frontend.pages.download-logo', compact('company', 'logoAvailable', 'logoExtension'));
    }
}
