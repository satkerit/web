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
            // Beranda
            'home' => ['name' => 'Beranda', 'route' => 'home', 'pattern' => '/'],
            
            // Tentang Kami (semua sub-menu)
            'about' => ['name' => 'Tentang Kami (Semua)', 'route' => 'about.*', 'pattern' => 'tentang-kami/*'],
            'about_company' => ['name' => 'Profil Perusahaan', 'route' => 'about.company', 'pattern' => 'tentang-kami/perusahaan'],
            'about_komisaris' => ['name' => 'Dewan Komisaris', 'route' => 'about.komisaris', 'pattern' => 'tentang-kami/dewan-komisaris'],
            'about_direksi' => ['name' => 'Dewan Direksi', 'route' => 'about.direksi', 'pattern' => 'tentang-kami/dewan-direksi'],
            'about_dps' => ['name' => 'Dewan Pengawas Syariah', 'route' => 'about.pengawas-syariah', 'pattern' => 'tentang-kami/dewan-pengawas-syariah'],
            'about_struktur' => ['name' => 'Struktur Organisasi', 'route' => 'about.struktur', 'pattern' => 'tentang-kami/struktur-organisasi'],
            'about_offices' => ['name' => 'Kantor', 'route' => 'about.offices', 'pattern' => 'tentang-kami/kantor'],
            
            // Produk & Layanan (semua sub-menu)
            'products' => ['name' => 'Produk & Layanan (Semua)', 'route' => 'products.*', 'pattern' => 'produk-layanan/*'],
            'products_simpanan' => ['name' => 'Simpanan Syariah', 'route' => 'products.simpanan-syariah', 'pattern' => 'produk-layanan/simpanan-syariah'],
            'products_pembiayaan' => ['name' => 'Pembiayaan Syariah', 'route' => 'products.pembiayaan-syariah', 'pattern' => 'produk-layanan/pembiayaan-syariah'],
            'products_deposito' => ['name' => 'Deposito Syariah', 'route' => 'products.deposito-syariah', 'pattern' => 'produk-layanan/deposito-syariah'],
            'products_kas_keliling' => ['name' => 'Kas Keliling', 'route' => 'products.kas-keliling', 'pattern' => 'produk-layanan/kas-keliling'],
            
            // Lelang
            'auctions' => ['name' => 'Lelang', 'route' => 'auctions.*', 'pattern' => 'lelang/*'],
            
            // Berita
            'news' => ['name' => 'Berita', 'route' => 'news.*', 'pattern' => 'berita/*'],
            
            // Informasi Umum / Laporan (semua sub-menu)
            'reports' => ['name' => 'Informasi Umum (Semua)', 'route' => 'reports.*', 'pattern' => 'informasi-umum/*'],
            'reports_keuangan' => ['name' => 'Laporan Keuangan Publikasi', 'route' => 'reports.keuangan-publikasi', 'pattern' => 'informasi-umum/laporan-keuangan-publikasi'],
            'reports_tata_kelola' => ['name' => 'Laporan Tata Kelola', 'route' => 'reports.tata-kelola', 'pattern' => 'informasi-umum/laporan-tata-kelola'],
            'reports_tahunan' => ['name' => 'Laporan Tahunan', 'route' => 'reports.tahunan', 'pattern' => 'informasi-umum/laporan-tahunan'],
            'reports_berkelanjutan' => ['name' => 'Laporan Tahunan Berkelanjutan', 'route' => 'reports.tahunan-berkelanjutan', 'pattern' => 'informasi-umum/laporan-tahunan-berkelanjutan'],
            
            // Karir
            'careers' => ['name' => 'Karir', 'route' => 'careers.*', 'pattern' => 'karir/*'],
            
            // Halaman Statis
            'contact' => ['name' => 'Hubungi Kami', 'route' => 'contact', 'pattern' => 'hubungi-kami'],
            'whistleblowing' => ['name' => 'Whistleblowing', 'route' => 'whistleblowing', 'pattern' => 'whistleblowing'],
            'pengaduan_nasabah' => ['name' => 'Pengaduan Nasabah', 'route' => 'pengaduan-nasabah', 'pattern' => 'pengaduan-nasabah'],
            'download_logo' => ['name' => 'Download Logo', 'route' => 'download-logo', 'pattern' => 'download-logo'],
            
            // Simulasi Pembiayaan
            'financing_simulation' => ['name' => 'Simulasi Pembiayaan', 'route' => 'financing-simulation', 'pattern' => 'simulasi-pembiayaan'],
        ];
    }

    public static function getSettings(): self
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::first() ?? self::create([
                'maintenance_mode' => false,
                'maintenance_message' => 'Website sedang dalam pemeliharaan untuk peningkatan layanan. Silakan kembali beberapa saat lagi.',
                'maintenance_pages' => [],
            ]);
        });
    }

    /**
     * Get fresh settings without cache
     */
    public static function getFreshSettings(): self
    {
        self::clearCache();
        return self::first() ?? self::create([
            'maintenance_mode' => false,
            'maintenance_message' => 'Website sedang dalam pemeliharaan untuk peningkatan layanan. Silakan kembali beberapa saat lagi.',
            'maintenance_pages' => [],
        ]);
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
        // Force clear jika menggunakan file/database cache
        Cache::flush(); // Uncomment jika perlu clear semua cache
    }

    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });
        
        static::updated(function () {
            self::clearCache();
        });
    }
}
