<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    /**
     * Generate responsive image srcset for hero slides
     * 
     * @param string $imagePath
     * @return array
     */
    public static function getResponsiveSrcset(string $imagePath): array
    {
        $url = Storage::url($imagePath);
        
        return [
            'src' => $url,
            'srcset' => $url, // In future, add multiple sizes here
            'sizes' => '100vw',
        ];
    }

    /**
     * Check if image should be preloaded
     * 
     * @param int $index
     * @return bool
     */
    public static function shouldPreload(int $index): bool
    {
        return $index === 0;
    }

    /**
     * Get loading attribute for image
     * 
     * @param int $index
     * @return string
     */
    public static function getLoadingAttribute(int $index): string
    {
        return $index === 0 ? 'eager' : 'lazy';
    }

    /**
     * Get decoding attribute for image
     * 
     * @param int $index
     * @return string
     */
    public static function getDecodingAttribute(int $index): string
    {
        return $index === 0 ? 'sync' : 'async';
    }

    /**
     * Get fetchpriority attribute for image
     * 
     * @param int $index
     * @return string|null
     */
    public static function getFetchPriority(int $index): ?string
    {
        return $index === 0 ? 'high' : null;
    }
}
