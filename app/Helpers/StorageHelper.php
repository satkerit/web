<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageHelper
{
    /**
     * Get the correct storage URL based on environment
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
        
        // Use Laravel's Storage facade which automatically handles environment
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Get asset URL (for public assets like CSS, JS)
     * 
     * @param string $path
     * @return string
     */
    public static function asset(string $path): string
    {
        $path = ltrim($path, '/');
        
        // In production with custom public path
        if (config('app.env') === 'production' && config('app.public_path')) {
            $baseUrl = rtrim(config('app.url'), '/');
            $publicPath = trim(config('app.public_path'), '/');
            return $publicPath ? "{$baseUrl}/{$publicPath}/{$path}" : "{$baseUrl}/{$path}";
        }
        
        // Default asset helper
        return asset($path);
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
     * Get storage path (physical path on disk)
     * 
     * @param string|null $path
     * @return string
     */
    public static function path(?string $path = ''): string
    {
        $path = ltrim($path, '/');
        return Storage::disk('public')->path($path);
    }
    
    /**
     * Store file to storage
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param string|null $name
     * @return string|false
     */
    public static function store($file, string $directory, ?string $name = null)
    {
        try {
            if ($name) {
                return $file->storeAs($directory, $name, 'public');
            }
            return $file->store($directory, 'public');
        } catch (\Exception $e) {
            Log::error('Storage error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete file from storage
     * 
     * @param string|null $path
     * @return bool
     */
    public static function delete(?string $path): bool
    {
        if (empty($path) || !self::exists($path)) {
            return false;
        }
        
        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            Log::error('Storage delete error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get storage configuration info
     * 
     * @return array
     */
    public static function getConfig(): array
    {
        return [
            'environment' => config('app.env'),
            'storage_root' => config('filesystems.disks.public.root'),
            'storage_url' => config('filesystems.disks.public.url'),
            'public_path' => config('app.public_path'),
            'is_production' => config('app.env') === 'production',
        ];
    }
}