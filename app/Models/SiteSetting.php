<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaturan Website';
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        return 'Site Settings';
    }

    protected $fillable = [
        'maintenance_mode',
        'maintenance_message',
        'maintenance_allowed_ips',
        'maintenance_end_time',
        'maintenance_pages',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'maintenance_end_time' => 'datetime',
        'maintenance_pages' => 'array',
    ];

    /**
     * Available pages for partial maintenance
     */
    public static function getAvailablePages(): array
    {
        return [
            'home' => ['name' => 'Beranda', 'route' => 'home', 'pattern' => '/'],
            'about' => ['name' => 'Tentang Kami', 'route' => 'about.*', 'pattern' => 'about/*'],
            'products' => ['name' => 'Produk & Layanan', 'route' => 'products.*', 'pattern' => 'produk/*'],
            'auctions' => ['name' => 'Lelang', 'route' => 'auctions.*', 'pattern' => 'lelang/*'],
            'news' => ['name' => 'Berita', 'route' => 'news.*', 'pattern' => 'berita/*'],
            'reports' => ['name' => 'Laporan', 'route' => 'reports.*', 'pattern' => 'laporan/*'],
            'contact' => ['name' => 'Hubungi Kami', 'route' => 'contact', 'pattern' => 'hubungi-kami'],
            'whistleblowing' => ['name' => 'Pengaduan', 'route' => 'whistleblowing', 'pattern' => 'pengaduan'],
        ];
    }

    public static function getSettings(): self
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::first() ?? self::create([
                'maintenance_mode' => false,
                'maintenance_message' => 'Website sedang dalam pemeliharaan untuk peningkatan layanan. Silakan kembali beberapa saat lagi.',
            ]);
        });
    }

    public function getAllowedIpsArray(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->maintenance_allowed_ips ?? '')));
    }

    public function isIpAllowed(string $ip): bool
    {
        $allowedIps = $this->getAllowedIpsArray();

        if (empty($allowedIps)) {
            return false;
        }

        return in_array($ip, $allowedIps);
    }

    public static function isMaintenanceMode(): bool
    {
        $settings = self::getSettings();

        if (!$settings->maintenance_mode) {
            return false;
        }

        // Check if maintenance end time has passed
        if ($settings->maintenance_end_time && $settings->maintenance_end_time->isPast()) {
            $settings->update(['maintenance_mode' => false]);
            self::clearCache();
            return false;
        }

        return true;
    }

    /**
     * Check if a specific page is under maintenance
     */
    public static function isPageUnderMaintenance(string $path): bool
    {
        $settings = self::getSettings();
        $maintenancePages = $settings->maintenance_pages ?? [];

        if (empty($maintenancePages)) {
            return false;
        }

        $availablePages = self::getAvailablePages();

        foreach ($maintenancePages as $pageKey) {
            if (!isset($availablePages[$pageKey])) {
                continue;
            }

            $page = $availablePages[$pageKey];
            $pattern = $page['pattern'];

            // Exact match for home
            if ($pattern === '/' && ($path === '/' || $path === '')) {
                return true;
            }

            // Pattern match with wildcard
            if (str_ends_with($pattern, '*')) {
                $prefix = rtrim($pattern, '/*');
                if (str_starts_with(ltrim($path, '/'), $prefix)) {
                    return true;
                }
            } else {
                // Exact match
                $cleanPath = ltrim($path, '/');
                if ($cleanPath === $pattern) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the page key from path
     */
    public static function getPageKeyFromPath(string $path): ?string
    {
        $settings = self::getSettings();
        $maintenancePages = $settings->maintenance_pages ?? [];
        $availablePages = self::getAvailablePages();

        foreach ($maintenancePages as $pageKey) {
            if (!isset($availablePages[$pageKey])) {
                continue;
            }

            $page = $availablePages[$pageKey];
            $pattern = $page['pattern'];

            if ($pattern === '/' && ($path === '/' || $path === '')) {
                return $pageKey;
            }

            if (str_ends_with($pattern, '*')) {
                $prefix = rtrim($pattern, '/*');
                if (str_starts_with(ltrim($path, '/'), $prefix)) {
                    return $pageKey;
                }
            } else {
                $cleanPath = ltrim($path, '/');
                if ($cleanPath === $pattern) {
                    return $pageKey;
                }
            }
        }

        return null;
    }

    /**
     * Get message for partial maintenance
     */
    public function getPageMaintenanceMessage(string $pageKey): string
    {
        $availablePages = self::getAvailablePages();
        $pageName = $availablePages[$pageKey]['name'] ?? 'Halaman ini';

        return "Halaman {$pageName} sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi.";
    }

    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
    }
}
