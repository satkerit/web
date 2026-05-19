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
        try {
            if (!class_exists('\App\Models\CompanyInfo')) return null;
            // Clear cache if it might be stale (doesn't have profile_image)
            $cached = Cache::get('company_info');
            if ($cached && $cached instanceof CompanyInfo && !array_key_exists('profile_image', $cached->getAttributes())) {
                Cache::forget('company_info');
            }

            return Cache::remember('company_info', self::CACHE_LONG, fn() => CompanyInfo::first());
        } catch (\Exception $e) {
            return null;
        }
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
        try {
            return Cache::remember(
                "hero_slides_{$limit}",
                self::CACHE_MEDIUM,
                function () use ($limit) {
                    if (!class_exists('\App\Models\HeroSlide')) return collect();
                    return HeroSlide::where('is_active', true)
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
                        ]);
                }
            );
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get products for homepage
     */
    public static function getHomeProducts(int $limit = 6)
    {
        try {
            return Cache::remember(
                "products_home_{$limit}",
                self::CACHE_LONG,
                function () use ($limit) {
                    if (!class_exists('\App\Models\Product')) return collect();
                    return Product::where('is_active', true)
                        ->orderBy('order_position')
                        ->limit($limit)
                        ->get(['id', 'name', 'slug', 'short_description', 'image', 'type']);
                }
            );
        } catch (\Exception $e) {
            return collect();
        }
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
        try {
            return Cache::remember("news_home_{$limit}", self::CACHE_MEDIUM, function () use ($limit) {
                if (!class_exists('\App\Models\News')) return collect();
                return News::where('is_published', true)
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc')
                    ->limit($limit)
                    ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'category']);
            });
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get upcoming auctions for homepage
     */
    public static function getHomeAuctions(int $limit = 3)
    {
        try {
            return Cache::remember(
                "auctions_home_{$limit}",
                self::CACHE_MEDIUM,
                function () use ($limit) {
                    if (!class_exists('\App\Models\Auction')) return collect();
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
        } catch (\Exception $e) {
            return collect();
        }
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
            } else {
                $query->where('type', '!=', 'kas_keliling');
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
     * Get news categories
     */
    public static function getNewsCategories()
    {
        return Cache::remember(
            'news_categories',
            self::CACHE_MEDIUM,
            fn() => News::where('is_published', true)
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category')
        );
    }

    /**
     * Get report years by type
     */
    public static function getReportYears(string $type)
    {
        return Cache::remember(
            "report_years_{$type}",
            self::CACHE_LONG,
            fn() => Report::where('type', $type)
                ->published()
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
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
                ->with([
                    'schedules' => function ($query) {
                        $query->where('is_active', true)
                            ->where('schedule_date', '>=', now()->toDateString())
                            ->orderBy('schedule_date');
                    }
                ])
                ->get()
        );
    }

    /**
     * Get Why Choose Us items
     */
    public static function getWhyChooseUs()
    {
        try {
            return Cache::remember('why_choose_us_items', self::CACHE_LONG, function () {
                if (!class_exists('\App\Models\WhyChooseUs')) return collect();
                return WhyChooseUs::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            });
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get Why Choose Us Settings
     */
    public static function getWhyChooseUsSettings()
    {
        try {
            return Cache::remember('why_choose_us_settings', self::CACHE_LONG, function () {
                if (!class_exists('\App\Models\WhyChooseUsSetting')) {
                    return (object)[
                        'section_title' => 'Mengapa Memilih Kami?',
                        'section_subtitle' => 'Keunggulan layanan perbankan syariah kami untuk Anda.',
                        'section_image' => null
                    ];
                }
                return WhyChooseUsSetting::first() ?? WhyChooseUsSetting::create([
                    'section_title' => 'Mengapa Memilih Kami?',
                    'section_subtitle' => 'Keunggulan layanan perbankan syariah kami untuk Anda.',
                ]);
            });
        } catch (\Exception $e) {
            return (object)[
                'section_title' => 'Mengapa Memilih Kami?',
                'section_subtitle' => 'Keunggulan layanan perbankan syariah kami untuk Anda.',
                'section_image' => null
            ];
        }
    }

    /**
     * Get active kas keliling schedules
     */
    public static function getKasKelilingSchedules()
    {
        return Cache::remember('kas_keliling_schedules', self::CACHE_MEDIUM, function () {
            $today = now()->startOfDay();
            $endDate = now()->addDays(4)->endOfDay();

            return \App\Models\KasKelilingSchedule::active()
                ->whereBetween('schedule_date', [
                    $today->toDateString(),
                    $endDate->toDateString()
                ])
                ->orderBy('schedule_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get()
                ->groupBy(function ($schedule) {
                    return $schedule->schedule_date->format('Y-m-d');
                });
        });
    }

    /**
     * Clear news related caches
     */
    public static function clearNewsCache(): void
    {
        Cache::forget('news_home_3');
        Cache::forget('news_categories');
    }

    /**
     * Clear kas keliling related caches
     */
    public static function clearKasKelilingCache(): void
    {
        Cache::forget('kas_keliling');
        Cache::forget('kas_keliling_schedules');
    }

    /**
     * Clear report related caches
     */
    public static function clearReportCache(?string $type = null): void
    {
        if ($type) {
            Cache::forget("report_years_{$type}");
        } else {
            foreach (['keuangan_publikasi', 'tata_kelola', 'tahunan', 'tahunan_berkelanjutan'] as $t) {
                Cache::forget("report_years_{$t}");
            }
        }
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
            'auctions_featured',
            'auctions_upcoming',
            'auctions_asset_types',
            'auctions_cities',
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
     * Clear auction related caches
     */
    public static function clearAuctionCache(): void
    {
        Cache::forget('auctions_home_3');
        Cache::forget('auctions_featured');
        Cache::forget('auctions_upcoming');
        Cache::forget('auctions_asset_types');
        Cache::forget('auctions_cities');
    }

    /**
     * Clear specific cache key
     */
    public static function clear(string $key): void
    {
        Cache::forget($key);
    }
}
