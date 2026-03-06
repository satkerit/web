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

if (!function_exists('format_rupiah')) {
    /**
     * Format number to Indonesian Rupiah currency
     *
     * @param int|float|string|null $amount
     * @param bool $showPrefix Show "Rp" prefix (default: true)
     * @return string
     */
    function format_rupiah($amount, bool $showPrefix = true): string
    {
        if ($amount === null || $amount === '') {
            return $showPrefix ? 'Rp 0' : '0';
        }

        // Convert to number if string
        if (is_string($amount)) {
            $amount = (float) preg_replace('/[^0-9.-]/', '', $amount);
        }

        $formatted = number_format($amount, 0, ',', '.');
        
        return $showPrefix ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('format_rupiah_short')) {
    /**
     * Format number to short Indonesian Rupiah (with K, M, B suffix)
     *
     * @param int|float|string|null $amount
     * @param bool $showPrefix Show "Rp" prefix (default: true)
     * @return string
     */
    function format_rupiah_short($amount, bool $showPrefix = true): string
    {
        if ($amount === null || $amount === '') {
            return $showPrefix ? 'Rp 0' : '0';
        }

        // Convert to number if string
        if (is_string($amount)) {
            $amount = (float) preg_replace('/[^0-9.-]/', '', $amount);
        }

        $prefix = $showPrefix ? 'Rp ' : '';

        if ($amount >= 1000000000) {
            return $prefix . number_format($amount / 1000000000, 1, ',', '.') . ' M'; // Miliar
        } elseif ($amount >= 1000000) {
            return $prefix . number_format($amount / 1000000, 1, ',', '.') . ' Jt'; // Juta
        } elseif ($amount >= 1000) {
            return $prefix . number_format($amount / 1000, 0, ',', '.') . ' Rb'; // Ribu
        }

        return $prefix . number_format($amount, 0, ',', '.');
    }
}

