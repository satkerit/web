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

if (!function_exists('csp_nonce')) {
    /**
     * Get the CSP nonce for the current request
     *
     * @return string
     */
    function csp_nonce(): string
    {
        return request()->attributes->get('csp_nonce', '');
    }
}

if (!function_exists('format_rupiah_short')) {
    /**
     * Format number to short Indonesian Rupiah (with K, Jt, M suffix)
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

        // Miliar (Billion)
        if ($amount >= 1000000000) {
            $value = $amount / 1000000000;
            // Format dengan 1 desimal jika ada, tanpa desimal jika bulat
            $formatted = $value == floor($value) ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $prefix . $formatted . ' M';
        }
        // Juta (Million)
        elseif ($amount >= 1000000) {
            $value = $amount / 1000000;
            // Format dengan 1 desimal jika ada, tanpa desimal jika bulat
            $formatted = $value == floor($value) ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $prefix . $formatted . ' Jt';
        }
        // Ribu (Thousand)
        elseif ($amount >= 1000) {
            $value = $amount / 1000;
            // Format dengan 1 desimal jika ada, tanpa desimal jika bulat
            $formatted = $value == floor($value) ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $prefix . $formatted . ' Rb';
        }

        // Kurang dari 1000, tampilkan full
        return $prefix . number_format($amount, 0, ',', '.');
    }
}
