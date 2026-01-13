<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanyInfo extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Info Perusahaan';
    }

    protected static function getAuditIdentifier(\Illuminate\Database\Eloquent\Model $model): string
    {
        return $model->name ?? 'Company Info';
    }
    protected $fillable = [
        'name',
        'tagline',
        'logo',
        'logo_footer',
        'favicon',
        'address',
        'phone',
        'fax',
        'whatsapp',
        'email',
        'email_contact',
        'email_complaint',
        'email_whistleblowing',
        'website',
        'description',
        'vision',
        'mission',
        'history',
        'organization_structure',
        'established_year',
        'stat_years_experience',
        'stat_branch_offices',
        'stat_total_assets',
        'stat_cash_offices',
        'stat_mobile_cash_offices',
        'legacy_visitor_count',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'tiktok',
        'ojk_license',
        'ojk_tagline',
        'lps_tagline',
        'lps_guarantee_amount',
        'footer_description',
        'meta_description',
        'meta_keywords',
        'operational_hours'
    ];

    protected $casts = [
        'established_year' => 'integer',
        'stat_years_experience' => 'integer',
        'stat_branch_offices' => 'integer',
        'stat_cash_offices' => 'integer',
        'stat_mobile_cash_offices' => 'integer',
        'legacy_visitor_count' => 'integer',
        'operational_hours' => 'array'
    ];

    /**
     * Get cached company info - use CacheService instead
     * @deprecated Use CacheService::getCompanyInfo() instead
     */
    public static function getInfo(): ?self
    {
        return Cache::remember('company_info', 86400, fn() => self::first());
    }

    /**
     * Clear company info cache
     */
    public static function clearCache(): void
    {
        Cache::forget('company_info');
    }

    /**
     * Boot method to clear cache on save
     */
    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }
}
