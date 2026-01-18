<?php

namespace App\Services;

use App\Models\CompanyInfo;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\News;
use App\Models\Auction;
use App\Models\Office;
use App\Models\BoardMember;
use App\Models\Report;
use App\Models\KasKeliling;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    // Cache durations in seconds
    const CACHE_SHORT = 1800;      // 30 minutes
    const CACHE_MEDIUM = 3600;     // 1 hour
    const CACHE_LONG = 86400;      // 24 hours

    /**
     * Get company info with caching
     */
    public static function getCompanyInfo(): ?CompanyInfo
    {
        return Cache::remember('company_info', self::CACHE_LONG, fn() => CompanyInfo::first());
    }

    /**
     * Get hero slides for homepage
     */
    public static function getHeroSlides(int $limit = 5)
    {
        return Cache::remember(
            "hero_slides_{$limit}",
            self::CACHE_MEDIUM,
            fn() =>
            HeroSlide::where('is_active', true)
                ->orderBy('order_position')
                ->limit($limit)
                ->get([
                    'id',
                    'title',
                    'subtitle',
                    'image',
                    'link_url',
                    'link_text',
                    'transition_type',
                    'transition_duration',
                    'show_title',
                    'show_subtitle',
                    'show_button'
                ])
        );
    }

    /**
     * Get products for homepage
     */
    public static function getHomeProducts(int $limit = 6)
    {
        return Cache::remember(
            "products_home_{$limit}",
            self::CACHE_MEDIUM,
            fn() =>
            Product::where('is_active', true)
                ->orderBy('order_position')
                ->limit($limit)
                ->get(['id', 'name', 'slug', 'short_description', 'image', 'type'])
        );
    }

    /**
     * Get products by type
     */
    public static function getProductsByType(string $type)
    {
        return Cache::remember(
            "products_{$type}",
            self::CACHE_MEDIUM,
            fn() =>
            Product::where('type', $type)
                ->where('is_active', true)
                ->orderBy('order_position')
                ->get()
        );
    }

    /**
     * Get latest news for homepage
     */
    public static function getHomeNews(int $limit = 3)
    {
        return Cache::remember(
            "news_home_{$limit}",
            self::CACHE_SHORT,
            fn() =>
            News::where('is_published', true)
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'category'])
        );
    }

    /**
     * Get upcoming auctions for homepage
     */
    public static function getHomeAuctions(int $limit = 3)
    {
        return Cache::remember(
            "auctions_home_{$limit}",
            self::CACHE_SHORT,
            fn() =>
            Auction::where('status', 'upcoming')
                ->orderBy('auction_date')
                ->limit($limit)
                ->get(['id', 'title', 'slug', 'location', 'starting_price', 'auction_date', 'images', 'asset_type', 'status'])
        );
    }

    /**
     * Get active offices
     */
    public static function getOffices(?string $type = null)
    {
        $key = $type ? "offices_{$type}" : "offices_all";

        return Cache::remember($key, self::CACHE_MEDIUM, function () use ($type) {
            $query = Office::where('is_active', true);
            if ($type) {
                $query->where('type', $type);
            }
            return $query->orderBy('type')->orderBy('name')->get();
        });
    }

    /**
     * Get board members by type
     */
    public static function getBoardMembers(string $type)
    {
        return Cache::remember(
            "board_members_{$type}",
            self::CACHE_LONG,
            fn() =>
            BoardMember::where('type', $type)
                ->orderBy('order_position')
                ->get()
        );
    }

    /**
     * Get reports by type with years
     */
    public static function getReportYears(string $type)
    {
        return Cache::remember(
            "report_years_{$type}",
            self::CACHE_MEDIUM,
            fn() =>
            Report::where('type', $type)
                ->where('is_published', true)
                ->distinct()
                ->pluck('year')
                ->sortDesc()
                ->values()
        );
    }

    /**
     * Get kas keliling with schedules
     */
    public static function getKasKeliling()
    {
        return Cache::remember(
            'kas_keliling',
            self::CACHE_MEDIUM,
            fn() =>
            KasKeliling::where('is_active', true)
                ->with(['schedules' => function ($query) {
                    $query->where('is_active', true)
                        ->where('schedule_date', '>=', now()->toDateString())
                        ->orderBy('schedule_date');
                }])
                ->get()
        );
    }

    /**
     * Clear all frontend caches
     */
    public static function clearAll(): void
    {
        $keys = [
            'company_info',
            'hero_slides_5',
            'products_home_6',
            'products_simpanan_syariah',
            'products_pembiayaan_syariah',
            'products_deposito_syariah',
            'news_home_3',
            'auctions_home_3',
            'offices_all',
            'offices_pusat',
            'offices_cabang',
            'offices_kas',
            'board_members_komisaris',
            'board_members_direksi',
            'board_members_pengawas_syariah',
            'kas_keliling',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear report years
        foreach (['keuangan_publikasi', 'tata_kelola', 'tahunan', 'tahunan_berkelanjutan'] as $type) {
            Cache::forget("report_years_{$type}");
        }
    }

    /**
     * Clear specific cache
     */
    public static function clear(string $key): void
    {
        Cache::forget($key);
    }
}
