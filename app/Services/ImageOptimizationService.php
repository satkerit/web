<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizationService
{
    /**
     * Generate optimized image URL with optional resizing
     */
    public static function url(string $path, ?int $width = null, ?int $height = null, string $fit = 'cover'): string
    {
        if (empty($path)) {
            return self::placeholder($width, $height);
        }

        // If it's already a full URL, return as is
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Return storage URL
        return Storage::url($path);
    }

    /**
     * Generate placeholder SVG
     */
    public static function placeholder(?int $width = 400, ?int $height = 300, string $color = '#e5e7eb'): string
    {
        $w = $width ?? 400;
        $h = $height ?? 300;
        $encodedColor = str_replace('#', '%23', $color);

        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$w} {$h}'%3E%3Crect fill='{$encodedColor}' width='100%25' height='100%25'/%3E%3C/svg%3E";
    }

    /**
     * Generate blur placeholder for image
     */
    public static function blurPlaceholder(?int $width = 400, ?int $height = 300): string
    {
        return self::placeholder($width, $height, '#f3f4f6');
    }

    /**
     * Generate lazy image HTML tag
     */
    public static function lazyImageTag(
        string $src,
        string $alt = '',
        string $class = '',
        bool $lazy = true,
        ?int $width = null,
        ?int $height = null,
        bool $priority = false
    ): string {
        $loading = $priority ? 'eager' : ($lazy ? 'lazy' : 'eager');
        $fetchPriority = $priority ? 'high' : 'auto';

        $w = $width ?? 400;
        $h = $height ?? 300;
        $placeholder = self::blurPlaceholder($w, $h);

        $widthAttr = $width ? "width=\"{$width}\"" : '';
        $heightAttr = $height ? "height=\"{$height}\"" : '';

        $escapedAlt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        $escapedClass = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');

        return "<img src=\"{$src}\" alt=\"{$escapedAlt}\" class=\"{$escapedClass} transition-opacity duration-300\" loading=\"{$loading}\" decoding=\"async\" fetchpriority=\"{$fetchPriority}\" {$widthAttr} {$heightAttr} style=\"background-image: url('{$placeholder}'); background-size: cover;\" onload=\"this.style.backgroundImage='none';\" onerror=\"this.onerror=null; this.style.backgroundImage='url({$placeholder})';\">";
    }

    /**
     * Get image dimensions from path (if available)
     */
    public static function getDimensions(string $path): ?array
    {
        if (empty($path) || !Storage::disk('public')->exists($path)) {
            return null;
        }

        try {
            $fullPath = Storage::disk('public')->path($path);
            $imageInfo = @getimagesize($fullPath);

            if ($imageInfo) {
                return [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                ];
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return null;
    }

    /**
     * Generate srcset for responsive images
     */
    public static function srcset(string $path, array $widths = [320, 640, 768, 1024, 1280, 1536]): ?string
    {
        // For now, return null - can be extended with image processing library
        return null;
    }

    /**
     * Check if image is SVG
     */
    public static function isSvg(string $path): bool
    {
        return Str::endsWith(strtolower($path), '.svg');
    }

    /**
     * Get appropriate sizes attribute based on layout
     */
    public static function getSizes(string $layout = 'default'): string
    {
        return match ($layout) {
            'full' => '100vw',
            'hero' => '100vw',
            'card' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw',
            'thumbnail' => '(max-width: 640px) 50vw, 200px',
            'avatar' => '48px',
            default => '(max-width: 768px) 100vw, 50vw',
        };
    }
}
