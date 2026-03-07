<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FinancingConfig extends Model
{
    use HasFactory, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Konfigurasi Pembiayaan';
    }

    protected $fillable = [
        'type',
        'calculation_type',
        'name',
        'description',
        'margin_rate',
        'min_principal',
        'max_principal',
        'available_tenors',
        'dp_enabled',
        'dp_min_percentage',
        'dp_max_percentage',
        'is_active',
    ];

    protected $casts = [
        'margin_rate' => 'decimal:4',
        'min_principal' => 'integer',
        'max_principal' => 'integer',
        'available_tenors' => 'array',
        'dp_enabled' => 'boolean',
        'dp_min_percentage' => 'decimal:2',
        'dp_max_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Cache key for financing configs
     */
    const CACHE_KEY = 'financing_configs_active';

    /**
     * Get active financing configs from cache or database
     */
    public static function getConfigs()
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::where('is_active', true)->get();
        });
    }

    /**
     * Clear financing configs cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Scope for active configs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot method to clear cache on model changes
     */
    protected static function booted(): void
    {
        $clearCache = function () {
            self::clearCache();
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    /**
     * Check if this financing uses profit sharing calculation
     */
    public function isProfitSharing(): bool
    {
        return $this->calculation_type === 'profit_sharing';
    }

    /**
     * Check if this financing uses margin calculation
     */
    public function isMargin(): bool
    {
        return $this->calculation_type === 'margin';
    }

    /**
     * Get the rate label based on calculation type
     */
    public function getRateLabel(): string
    {
        return $this->isProfitSharing() ? 'Proyeksi Bagi Hasil' : 'Margin';
    }
}
