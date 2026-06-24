<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageHelper
{
    /**
     * Get the correct storage URL based on environment
     * Works automatically in both development and production
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
        
        // Remove 'storage/' prefix if present (avoid double storage in URL)
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        
        // Use Laravel's Storage facade which automatically handles environment
        // This will use the STORAGE_URL from .env
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Get asset URL (for public assets like CSS, JS)
     * Works automatically in both development and production
     * 
     * @param string $path
     * @return string
     */
    public static function asset(string $path): string
    {
        $path = ltrim($path, '/');
        
        // Use standard Laravel asset() helper
        // It automatically uses APP_URL from .env
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
        $storageMode = config('filesystems.storage_mode', 'development');
        $links = config('filesystems.links');
        
        return [
            'mode' => $storageMode,
            'environment' => config('app.env'),
            'storage_root' => config('filesystems.disks.public.root'),
            'storage_url' => config('filesystems.disks.public.url'),
            'production_public_path' => config('app.production_public_path'),
            'symlink_from' => array_keys($links)[0] ?? null,
            'symlink_to' => array_values($links)[0] ?? null,
            'is_production' => $storageMode === 'production',
        ];
    }
    
    /**
     * Verify storage link is working
     * 
     * @return array
     */
    public static function verifyStorageLink(): array
    {
        $config = self::getConfig();
        $linkFrom = $config['symlink_from'];
        $linkTo = $config['symlink_to'];
        
        $result = [
            'link_exists' => false,
            'link_valid' => false,
            'target_exists' => false,
            'link_path' => $linkFrom,
            'target_path' => $linkTo,
            'message' => '',
        ];
        
        // Check if target directory exists
        if ($linkTo && is_dir($linkTo)) {
            $result['target_exists'] = true;
        } else {
            $result['message'] = 'Target directory does not exist';
            return $result;
        }
        
        // Check if link exists
        if ($linkFrom && (file_exists($linkFrom) || is_link($linkFrom))) {
            $result['link_exists'] = true;
            
            // Check if it's a valid symlink
            if (is_link($linkFrom)) {
                $actualTarget = readlink($linkFrom);
                // Normalize paths for comparison
                $normalizedActual = str_replace('\\', '/', realpath($actualTarget) ?: $actualTarget);
                $normalizedExpected = str_replace('\\', '/', realpath($linkTo) ?: $linkTo);
                
                if ($normalizedActual === $normalizedExpected) {
                    $result['link_valid'] = true;
                    $result['message'] = 'Storage link is working correctly';
                } else {
                    $result['message'] = "Link points to wrong location: {$actualTarget}";
                }
            } else {
                // Not a symlink, but file exists - could be junction on Windows or regular directory
                // Check by comparing real paths
                $linkRealPath = realpath($linkFrom);
                $targetRealPath = realpath($linkTo);
                
                // Normalize paths for comparison
                $linkNormalized = $linkRealPath ? strtolower(str_replace('\\', '/', $linkRealPath)) : '';
                $targetNormalized = $targetRealPath ? strtolower(str_replace('\\', '/', $targetRealPath)) : '';
                
                if ($linkNormalized && $targetNormalized && $linkNormalized === $targetNormalized) {
                    $result['link_valid'] = true;
                    if (PHP_OS_FAMILY === 'Windows') {
                        $result['message'] = 'Storage link is working correctly (Windows junction)';
                    } else {
                        $result['message'] = 'Storage link is working correctly';
                    }
                } else {
                    $result['message'] = 'Path exists but is not a symbolic link';
                }
            }
        } else {
            $result['message'] = 'Storage link does not exist. Run: php artisan storage:link-auto';
        }
        
        return $result;
    }
}