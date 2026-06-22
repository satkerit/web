<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * Service khusus untuk konversi WebP
 * Mendukung AVIF jika extension tersedia
 */
class WebPConverterService
{
    /**
     * Convert image to WebP format dengan multiple sizes
     * 
     * @param string $originalPath
     * @param array $options
     * @return array
     */
    public static function convertToWebP(string $originalPath, array $options = []): array
    {
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
            
            $quality = $options['quality'] ?? 85;
            $breakpoints = $options['breakpoints'] ?? ImageCompressionService::BREAKPOINTS;
            
            $webpVersions = [];

            // Generate WebP untuk setiap breakpoint
            foreach ($breakpoints as $name => $width) {
                $webpFilename = "{$filename}_{$name}.webp";
                $webpPath = "{$directory}/{$webpFilename}";

                $resized = clone $image;
                $resized->scaleDown(width: $width);
                $encoded = $resized->toWebp(quality: $quality);
                
                $disk->put($webpPath, (string) $encoded);
                $webpVersions[$name] = $webpPath;
                
                Log::info("WebP created: {$webpPath}", [
                    'size' => $disk->size($webpPath),
                    'width' => $width
                ]);
            }

            return $webpVersions;

        } catch (\Exception $e) {
            Log::error('WebP conversion failed: ' . $e->getMessage(), [
                'path' => $originalPath
            ]);
            
            return [];
        }
    }

    /**
     * Convert image to AVIF format dengan multiple sizes
     * 
     * @param string $originalPath
     * @param array $options
     * @return array
     */
    public static function convertToAVIF(string $originalPath, array $options = []): array
    {
        try {
            $disk = Storage::disk('public');
            
            if (!$disk->exists($originalPath)) {
                throw new \Exception("Original image not found: {$originalPath}");
            }

            if (!self::isAVIFSupported()) {
                Log::info('AVIF conversion skipped: GD AVIF support not available');
                return [];
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($disk->path($originalPath));
            
            $pathInfo = pathinfo($originalPath);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            
            $quality = $options['quality'] ?? 85;
            $breakpoints = $options['breakpoints'] ?? ImageCompressionService::BREAKPOINTS;
            
            $avifVersions = [];

            // Generate AVIF untuk setiap breakpoint
            foreach ($breakpoints as $name => $width) {
                $avifFilename = "{$filename}_{$name}.avif";
                $avifPath = "{$directory}/{$avifFilename}";

                $resized = clone $image;
                $resized->scaleDown(width: $width);
                $encoded = $resized->toAvif(quality: $quality);
                
                $disk->put($avifPath, (string) $encoded);
                $avifVersions[$name] = $avifPath;
                
                Log::info("AVIF created: {$avifPath}", [
                    'size' => $disk->size($avifPath),
                    'width' => $width
                ]);
            }

            return $avifVersions;

        } catch (\Exception $e) {
            Log::error('AVIF conversion failed: ' . $e->getMessage(), [
                'path' => $originalPath
            ]);
            
            return [];
        }
    }

    /**
     * Get existing responsive WebP versions
     */
    public static function getExistingResponsiveWebP(string $originalPath): array
    {
        return ImageCompressionService::getExistingResponsiveWebP($originalPath);
    }

    /**
     * Get existing responsive AVIF versions
     */
    public static function getExistingResponsiveAVIF(string $originalPath): array
    {
        return ImageCompressionService::getExistingResponsiveAVIF($originalPath);
    }

    /**
     * Get single WebP version (desktop/main)
     */
    public static function getExistingWebP(string $originalPath): ?string
    {
        return ImageCompressionService::getExistingWebP($originalPath);
    }

    /**
     * Get single AVIF version (desktop/main)
     */
    public static function getExistingAVIF(string $originalPath): ?string
    {
        return ImageCompressionService::getExistingAVIF($originalPath);
    }

    /**
     * Check if WebP is supported by GD
     */
    public static function isWebPSupported(): bool
    {
        if (!function_exists('gd_info')) {
            return false;
        }
        
        $gdInfo = gd_info();
        return isset($gdInfo['WebP Support']) && $gdInfo['WebP Support'];
    }

    /**
     * Check if AVIF is supported by GD
     */
    public static function isAVIFSupported(): bool
    {
        return ImageCompressionService::isAVIFSupported();
    }

    /**
     * Batch convert multiple images to WebP
     * Useful untuk command/migration
     */
    public static function batchConvert(array $imagePaths, array $options = []): array
    {
        $results = [
            'success' => [],
            'failed' => []
        ];

        foreach ($imagePaths as $path) {
            try {
                $webpVersions = self::convertToWebP($path, $options);
                
                if (!empty($webpVersions)) {
                    $results['success'][$path] = $webpVersions;
                } else {
                    $results['failed'][] = $path;
                }
            } catch (\Exception $e) {
                $results['failed'][] = $path;
                Log::error("Batch WebP conversion failed for: {$path}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $results;
    }
}
