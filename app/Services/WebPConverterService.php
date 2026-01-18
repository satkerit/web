<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class WebPConverterService
{
    /**
     * Convert image to WebP format for better compression
     * 
     * @param string $imagePath
     * @param int $quality
     * @return string|null WebP image path or null if conversion failed
     */
    public static function convertToWebP(string $imagePath, int $quality = 80): ?string
    {
        $webpPath = self::getWebPPath($imagePath);
        
        if (Storage::exists($webpPath)) {
            return $webpPath;
        }

        $fullPath = Storage::path($imagePath);
        
        if (!file_exists($fullPath)) {
            return null;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            
            $webpFullPath = Storage::path($webpPath);
            $directory = dirname($webpFullPath);
            
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Convert to WebP
            $image->toWebp($quality)->save($webpFullPath);
            
            return $webpPath;
            
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get WebP image path
     */
    private static function getWebPPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        return $directory . '/' . $filename . '.webp';
    }

    /**
     * Generate responsive WebP images
     */
    public static function generateResponsiveWebP(string $imagePath): array
    {
        $sizes = [
            'mobile' => ['width' => 640, 'quality' => 75],
            'tablet' => ['width' => 1024, 'quality' => 80],
            'desktop' => ['width' => 1920, 'quality' => 80],
        ];

        $responsiveWebP = [];
        $fullPath = Storage::path($imagePath);

        if (!file_exists($fullPath)) {
            return [];
        }

        try {
            $manager = new ImageManager(new Driver());
            
            foreach ($sizes as $size => $config) {
                $webpPath = self::getResponsiveWebPPath($imagePath, $size);
                
                if (!Storage::exists($webpPath)) {
                    $image = $manager->read($fullPath);
                    $image->scale(width: $config['width']);
                    
                    $webpFullPath = Storage::path($webpPath);
                    $directory = dirname($webpFullPath);
                    
                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    $image->toWebp($config['quality'])->save($webpFullPath);
                }
                
                $responsiveWebP[$size] = $webpPath;
            }
            
            return $responsiveWebP;
            
        } catch (\Exception $e) {
            \Log::error('Responsive WebP generation failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get responsive WebP path
     */
    private static function getResponsiveWebPPath(string $originalPath, string $size): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        return $directory . '/' . $size . '_' . $filename . '.webp';
    }
}