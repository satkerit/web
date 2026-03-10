<?php

namespace App\Helpers;

class CurrencyFormatter
{
    /**
     * Format number to Indonesian Rupiah with optimal scale
     * Automatically scales from ribuan to ratusan miliar
     * 
     * Examples:
     * 1000 -> Rp 1 ribu
     * 10000 -> Rp 10 ribu
     * 100000 -> Rp 100 ribu
     * 1000000 -> Rp 1 juta
     * 10000000 -> Rp 10 juta
     * 100000000 -> Rp 100 juta
     * 1000000000 -> Rp 1 miliar
     * 10000000000 -> Rp 10 miliar
     * 100000000000 -> Rp 100 miliar
     */
    public static function formatOptimal($number): string
    {
        $number = (int) $number;
        
        if ($number == 0) {
            return 'Rp 0';
        }
        
        $abs = abs($number);
        $sign = $number < 0 ? '-' : '';
        
        // Ratusan Miliar (100 Miliar ke atas)
        if ($abs >= 100000000000) {
            $value = $abs / 1000000000;
            $formatted = $value >= 100 ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' miliar';
        }
        
        // Puluhan Miliar (10 Miliar - 99 Miliar)
        if ($abs >= 10000000000) {
            $value = $abs / 1000000000;
            $formatted = number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' miliar';
        }
        
        // Miliar (1 Miliar - 9 Miliar)
        if ($abs >= 1000000000) {
            $value = $abs / 1000000000;
            $formatted = number_format($value, 2, ',', '.');
            return $sign . 'Rp ' . $formatted . ' miliar';
        }
        
        // Ratusan Juta (100 Juta ke atas)
        if ($abs >= 100000000) {
            $value = $abs / 1000000;
            $formatted = $value >= 100 ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' juta';
        }
        
        // Puluhan Juta (10 Juta - 99 Juta)
        if ($abs >= 10000000) {
            $value = $abs / 1000000;
            $formatted = number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' juta';
        }
        
        // Juta (1 Juta - 9 Juta)
        if ($abs >= 1000000) {
            $value = $abs / 1000000;
            $formatted = number_format($value, 2, ',', '.');
            return $sign . 'Rp ' . $formatted . ' juta';
        }
        
        // Ratusan Ribu (100 Ribu ke atas)
        if ($abs >= 100000) {
            $value = $abs / 1000;
            $formatted = $value >= 100 ? number_format($value, 0, ',', '.') : number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' ribu';
        }
        
        // Puluhan Ribu (10 Ribu - 99 Ribu)
        if ($abs >= 10000) {
            $value = $abs / 1000;
            $formatted = number_format($value, 1, ',', '.');
            return $sign . 'Rp ' . $formatted . ' ribu';
        }
        
        // Ribu (1 Ribu - 9 Ribu)
        if ($abs >= 1000) {
            $value = $abs / 1000;
            $formatted = number_format($value, 2, ',', '.');
            return $sign . 'Rp ' . $formatted . ' ribu';
        }
        
        // Kurang dari 1000
        return $sign . 'Rp ' . number_format($abs, 0, ',', '.');
    }

    /**
     * Format number to full Rupiah format (with decimal places)
     * 
     * Examples:
     * 1000000 -> Rp 1.000.000
     * 50000000 -> Rp 50.000.000
     */
    public static function formatFull($number): string
    {
        $number = (int) $number;
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    /**
     * Parse formatted currency string to number
     * 
     * Examples:
     * "Rp 1 juta" -> 1000000
     * "Rp 50 juta" -> 50000000
     * "Rp 1.000.000" -> 1000000
     */
    public static function parseToNumber($formatted): int
    {
        // Remove "Rp " prefix
        $formatted = str_replace('Rp ', '', $formatted);
        
        // Handle miliar
        if (strpos($formatted, 'miliar') !== false) {
            $value = (float) str_replace([' miliar', '.', ','], ['', '', '.'], $formatted);
            return (int) ($value * 1000000000);
        }
        
        // Handle juta
        if (strpos($formatted, 'juta') !== false) {
            $value = (float) str_replace([' juta', '.', ','], ['', '', '.'], $formatted);
            return (int) ($value * 1000000);
        }
        
        // Handle ribu
        if (strpos($formatted, 'ribu') !== false) {
            $value = (float) str_replace([' ribu', '.', ','], ['', '', '.'], $formatted);
            return (int) ($value * 1000);
        }
        
        // Handle full format (Rp 1.000.000)
        $value = (float) str_replace(['.', ','], ['', '.'], $formatted);
        return (int) $value;
    }
}
