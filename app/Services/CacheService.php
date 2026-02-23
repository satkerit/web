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
use App\Models\WhyChooseUs;
use App\Models\WhyChooseUsSetting;
use App\Models\SiteSetting;
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
        // Clear cache if it might be stale (doesn't have profile_image)
        $cached = Cache::get('company_info');
        if ($cached && !array_key_exists('profile_image', $cached->getAttributes())) {
            Cache::forget('company_info');
        }

        return Cache::remember('company_info', self::CACHE_LONG, fn() => CompanyInfo::first());
    }

    /**
     * Get hero slides for homepage with dynamic limit from settings
     */
    public static function getHeroSlidesDynamic()
    {
        // Get limit from site settings, default to 5 if not set
        $limit = SiteSetting::getSettings()->hero_slide_limit ?? 5;

        // Ensure limit is within valid range (1-20)
        $limit = max(1, min(20, $limit));

        return self::getHeroSlides($limit);
    }

    /**
     * Get hero slides for homepage with specific limit
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
            function () use ($limit) {
                try {
                    return Auction::whereIn('status', ['published', 'registration_open', 'auction_scheduled', 'sold'])
                        ->where(function ($query) {
                            $query->whereNull('auction_date')
                                ->orWhere('auction_date', '>', now())
                                ->orWhere('status', 'sold');
                        })
                        ->orderByRaw("CASE WHEN status = 'sold' THEN 1 ELSE 0 END")
                        ->orderBy('auction_date', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->limit($limit)
                        ->get(['id', 'title', 'slug', 'city', 'limit_price', 'auction_date', 'images', 'asset_type', 'status']);
                } catch (\Exception $e) {
                    // Fallback jika ada masalah dengan kolom
                    \Log::error('Error getting home auctions: ' . $e->getMessage());
                    return collect(); // Return empty collection
                }
            }
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
     * Get Why Choose Us items
     */
    public static function getWhyChooseUs()
    {
        return Cache::remember(
            'why_choose_us_items',
            self::CACHE_MEDIUM,
            fn() => WhyChooseUs::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }

    /**
     * Get Why Choose Us Settings
     */
    public static function getWhyChooseUsSettings()
    {
        return Cache::remember(
            'why_choose_us_settings',
            self::CACHE_LONG,
            fn() => WhyChooseUsSetting::getSettings()
        );
    }

    /**
     * Clear all frontend caches
     */
    public static function clearAll(): void
    {
        $keys = [
            'company_info',
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
            'why_choose_us_items',
            'why_choose_us_settings',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear hero slides cache for all possible limits (1-20)
        for ($i = 1; $i <= 20; $i++) {
            Cache::forget("hero_slides_{$i}");
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
