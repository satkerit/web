<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Service untuk kompresi dan optimasi gambar otomatis
 * Menghasilkan multiple variants untuk responsive images
 */
class ImageCompressionService
{
    // Breakpoints untuk responsive images
    const BREAKPOINTS = [
        'mobile' => 640,   // sm
        'tablet' => 1024,  // lg
        'desktop' => 1920, // xl
    ];

    // Quality settings
    const QUALITY_HIGH = 90;
    const QUALITY_MEDIUM = 85;
    const QUALITY_LOW = 75;

    /**
     * Proses gambar saat upload - generate multiple variants
     * 
     * @param string $originalPath Path gambar original di storage
     * @param array $options Opsi tambahan (quality, formats, breakpoints)
     * @return array Array berisi path semua variant yang dihasilkan
     */
    public static function processUploadedImage(string $originalPath, array $options = []): array
    {
        $originalMemoryLimit = ini_get('memory_limit');
        $originalTimeLimit = ini_get('max_execution_time');
        
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        try {
            $disk = Storage::disk('public');
            
            if (!$disk->exists($originalPath)) {
                throw new \Exception("Original image not found: {$originalPath}");
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($disk->path($originalPath));
            
            $pathInfo = pathinfo($originalPath);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            
            $results = [
                'original' => $originalPath,
                'compressed' => [],
                'webp' => [],
                'responsive' => []
            ];

            // 1. Generate compressed JPEG/PNG version (original size)
            $compressedPath = self::generateCompressed($image, $directory, $filename, $options);
            if ($compressedPath) {
                $results['compressed'] = $compressedPath;
            }

            // 2. Generate WebP versions untuk semua breakpoints
            $webpVersions = self::generateWebPVersions($image, $directory, $filename, $options);
            $results['webp'] = $webpVersions;

            // 3. Generate responsive JPEG versions
            $responsiveVersions = self::generateResponsiveVersions($image, $directory, $filename, $options);
            $results['responsive'] = $responsiveVersions;

            Log::info('Image processing completed', [
                'original' => $originalPath,
                'variants_created' => count($results['webp']) + count($results['responsive']) + 1
            ]);

            return $results;

        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage(), [
                'path' => $originalPath,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'original' => $originalPath,
                'compressed' => $originalPath,
                'webp' => [],
                'responsive' => []
            ];
        } finally {
            ini_set('memory_limit', $originalMemoryLimit);
            ini_set('max_execution_time', $originalTimeLimit);
        }
    }

    /**
     * Generate compressed version (same size, better compression)
     */
    protected static function generateCompressed($image, string $directory, string $filename, array $options): string
    {
        $quality = $options['quality'] ?? self::QUALITY_MEDIUM;
        $compressedFilename = "{$filename}_compressed.jpg";
        $compressedPath = "{$directory}/{$compressedFilename}";

        $encoded = $image->toJpeg(quality: $quality, progressive: true);
        Storage::disk('public')->put($compressedPath, (string) $encoded);

        return $compressedPath;
    }

    /**
     * Generate WebP versions untuk semua breakpoints
     */
    protected static function generateWebPVersions($image, string $directory, string $filename, array $options): array
    {
        $quality = $options['webp_quality'] ?? self::QUALITY_MEDIUM;
        $breakpoints = $options['breakpoints'] ?? self::BREAKPOINTS;
        $webpVersions = [];

        foreach ($breakpoints as $name => $width) {
            $webpFilename = "{$filename}_{$name}.webp";
            $webpPath = "{$directory}/{$webpFilename}";

            $resized = clone $image;
            $resized->scaleDown(width: $width);
            $encoded = $resized->toWebp(quality: $quality);
            
            Storage::disk('public')->put($webpPath, (string) $encoded);
            $webpVersions[$name] = $webpPath;
        }

        return $webpVersions;
    }

    /**
     * Generate responsive JPEG versions (fallback untuk browser lama)
     */
    protected static function generateResponsiveVersions($image, string $directory, string $filename, array $options): array
    {
        $quality = $options['quality'] ?? self::QUALITY_MEDIUM;
        $breakpoints = $options['breakpoints'] ?? self::BREAKPOINTS;
        $responsiveVersions = [];

        foreach ($breakpoints as $name => $width) {
            $responsiveFilename = "{$filename}_{$name}.jpg";
            $responsivePath = "{$directory}/{$responsiveFilename}";

            $resized = clone $image;
            $resized->scaleDown(width: $width);
            $encoded = $resized->toJpeg(quality: $quality, progressive: true);
            
            Storage::disk('public')->put($responsivePath, (string) $encoded);
            $responsiveVersions[$name] = $responsivePath;
        }

        return $responsiveVersions;
    }

    /**
     * Get existing compressed version atau fallback ke original
     */
    public static function getExistingCompressed(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        $compressedPath = "{$directory}/{$filename}_compressed.jpg";
        
        if (Storage::disk('public')->exists($compressedPath)) {
            return $compressedPath;
        }
        
        return $originalPath;
    }

    /**
     * Get existing responsive WebP versions
     */
    public static function getExistingResponsiveWebP(string $originalPath): array
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        $webpVersions = [];
        
        foreach (self::BREAKPOINTS as $name => $width) {
            $webpPath = "{$directory}/{$filename}_{$name}.webp";
            if (Storage::disk('public')->exists($webpPath)) {
                $webpVersions[$name] = $webpPath;
            }
        }
        
        return $webpVersions;
    }

    /**
     * Get single WebP version (main/desktop)
     */
    public static function getExistingWebP(string $originalPath): ?string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        $webpPath = "{$directory}/{$filename}_desktop.webp";
        
        if (Storage::disk('public')->exists($webpPath)) {
            return $webpPath;
        }
        
        return null;
    }

    /**
     * Compress an existing image for web (resize + re-encode)
     */
    public static function compressForWeb(string $relativePath, int $quality = 80, int $maxWidth = 1200): ?string
    {
        try {
            $disk = Storage::disk('public');

            if (!$disk->exists($relativePath)) {
                return null;
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($disk->path($relativePath));

            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

            $encoded = match ($extension) {
                'webp' => $image->toWebp(quality: $quality),
                'png' => $image->toPng(),
                default => $image->toJpeg(quality: $quality, progressive: true),
            };

            $compressedPath = $relativePath;
            $disk->put($compressedPath, (string) $encoded);

            return $compressedPath;
        } catch (\Exception $e) {
            Log::error('compressForWeb failed: ' . $e->getMessage(), ['path' => $relativePath]);
            return null;
        }
    }

    /**
     * Delete all variants of an image
     */
    public static function deleteImageVariants(string $originalPath): void
    {
        $disk = Storage::disk('public');
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        // Delete compressed version
        $compressedPath = "{$directory}/{$filename}_compressed.jpg";
        if ($disk->exists($compressedPath)) {
            $disk->delete($compressedPath);
        }

        // Delete all responsive versions
        foreach (self::BREAKPOINTS as $name => $width) {
            $webpPath = "{$directory}/{$filename}_{$name}.webp";
            $jpegPath = "{$directory}/{$filename}_{$name}.jpg";
            
            if ($disk->exists($webpPath)) {
                $disk->delete($webpPath);
            }
            if ($disk->exists($jpegPath)) {
                $disk->delete($jpegPath);
            }
        }

        // Delete original
        if ($disk->exists($originalPath)) {
            $disk->delete($originalPath);
        }
    }
}
