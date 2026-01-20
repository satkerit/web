<?php

use App\Helpers\StorageHelper;

if (!function_exists('storage_url')) {
    /**
     * Get storage URL for uploaded files
     * Works in both development and production environments
     *
     * @param string|null $path
     * @return string
     */
    function storage_url(?string $path): string
    {
        return StorageHelper::url($path);
    }
}

if (!function_exists('storage_asset')) {
    /**
     * Get asset URL (for public assets like CSS, JS)
     * Works in both development and production environments
     *
     * @param string $path
     * @return string
     */
    function storage_asset(string $path): string
    {
        return StorageHelper::asset($path);
    }
}

if (!function_exists('storage_exists')) {
    /**
     * Check if file exists in storage
     *
     * @param string|null $path
     * @return bool
     */
    function storage_exists(?string $path): bool
    {
        return StorageHelper::exists($path);
    }
}

if (!function_exists('storage_path_url')) {
    /**
     * Alias for storage_url (for backward compatibility)
     *
     * @param string|null $path
     * @return string
     */
    function storage_path_url(?string $path): string
    {
        return StorageHelper::url($path);
    }
}
