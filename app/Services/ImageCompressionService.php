<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Compress and optimize image for web display
     *
     * @param string $imagePath Original image path in storage
     * @param int $quality Quality for compression (1-100)
     * @param int|null $maxWidth Maximum width
     * @return string Compressed image path
     */
    public static function compressForWeb(string $imagePath, int $quality = 75, ?int $maxWidth = 1920): string
    {
        // Check if compressed version already exists
        $compressedPath = self::getCompressedPath($imagePath);

        if (Storage::exists($compressedPath)) {
            return $compressedPath;
        }

        // Get full path
        $fullPath = Storage::path($imagePath);

        if (!file_exists($fullPath)) {
            return $imagePath; // Return original if not found
        }

        try {
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());

            // Load and compress image
            $image = $manager->read($fullPath);

            // Resize if too large
            if ($maxWidth && $image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            // Optimize and save
            $compressedFullPath = Storage::path($compressedPath);

            // Create directory if not exists
            $directory = dirname($compressedFullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save with compression
            $image->toJpeg($quality)->save($compressedFullPath);

            return $compressedPath;
        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());
            return $imagePath; // Return original on error
        }
    }

    /**
     * Get already compressed image path if exists, otherwise return original
     */
    public static function getExistingCompressed(string $imagePath): string
    {
        $compressedPath = self::getCompressedPath($imagePath);

        if (Storage::disk('public')->exists($compressedPath)) {
            return $compressedPath;
        }

        return $imagePath;
    }

    /**
     * Get compressed image path
     */
    public static function getCompressedPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        return $directory . '/compressed_' . $filename . '.' . $extension;
    }

    /**
     * Generate responsive image sizes
     *
     * @param string $imagePath
     * @return array
     */
    public static function generateResponsiveSizes(string $imagePath): array
    {
        $sizes = [
            'mobile' => ['width' => 640, 'quality' => 70],
            'tablet' => ['width' => 1024, 'quality' => 75],
            'desktop' => ['width' => 1920, 'quality' => 75],
        ];

        $responsiveImages = [];
        $fullPath = Storage::path($imagePath);

        if (!file_exists($fullPath)) {
            return [];
        }

        try {
            $manager = new ImageManager(new Driver());

            foreach ($sizes as $size => $config) {
                $responsivePath = self::getResponsivePath($imagePath, $size);

                if (!Storage::exists($responsivePath)) {
                    $image = $manager->read($fullPath);

                    // Scale to width
                    $image->scale(width: $config['width']);

                    $responsiveFullPath = Storage::path($responsivePath);
                    $directory = dirname($responsiveFullPath);

                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $image->toJpeg($config['quality'])->save($responsiveFullPath);
                }

                $responsiveImages[$size] = $responsivePath;
            }

            return $responsiveImages;
        } catch (\Exception $e) {
            \Log::error('Responsive image generation failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get responsive image path
     */
    private static function getResponsivePath(string $originalPath, string $size): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        return $directory . '/' . $size . '_' . $filename . '.' . $extension;
    }

    /**
     * Clean up old compressed images
     */
    public static function cleanupCompressed(string $originalPath): void
    {
        $compressedPath = self::getCompressedPath($originalPath);

        if (Storage::exists($compressedPath)) {
            Storage::delete($compressedPath);
        }

        // Clean responsive sizes
        $sizes = ['mobile', 'tablet', 'desktop'];
        foreach ($sizes as $size) {
            $responsivePath = self::getResponsivePath($originalPath, $size);
            if (Storage::exists($responsivePath)) {
                Storage::delete($responsivePath);
            }
        }
    }
}
