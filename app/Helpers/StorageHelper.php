<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class StorageHelper
{
    /**
     * Get the correct storage URL for production environment
     * 
     * @param string|null $path
     * @return string
     */
    public static function url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Check if it's already a full URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        
        // For production environment with custom storage path
        if (App::environment('production') && config('storage-production.production_paths.storage_url')) {
            $baseUrl = config('storage-production.production_paths.storage_url');
            $baseUrl = rtrim($baseUrl, '/');
            return $baseUrl . '/' . $path;
        }
        
        // Default Laravel storage URL
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Check if file exists in storage
     * 
     * @param string|null $path
     * @return bool
     */
    public static function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        
        return Storage::disk('public')->exists($path);
    }
    
    /**
     * Get file size in human readable format
     * 
     * @param string|null $path
     * @return string
     */
    public static function size(?string $path): string
    {
        if (empty($path) || !self::exists($path)) {
            return '0 B';
        }
        
        $bytes = Storage::disk('public')->size($path);
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
    
    /**
     * Get file last modified time
     * 
     * @param string|null $path
     * @return string
     */
    public static function lastModified(?string $path): string
    {
        if (empty($path) || !self::exists($path)) {
            return '-';
        }
        
        $timestamp = Storage::disk('public')->lastModified($path);
        return date('d M Y H:i', $timestamp);
    }
    
    /**
     * Create storage symlink for production
     * 
     * @return bool
     */
    public static function createProductionSymlink(): bool
    {
        if (!App::environment('production')) {
            return false;
        }
        
        $target = config('storage-production.production_paths.storage_target');
        $link = config('storage-production.production_paths.public_storage_path');
        
        if (!$target || !$link) {
            return false;
        }
        
        // Create target directory if it doesn't exist
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }
        
        // Remove existing symlink if it exists
        if (is_link($link)) {
            unlink($link);
        }
        
        // Create new symlink
        return symlink($target, $link);
    }
}