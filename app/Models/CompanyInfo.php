<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
        // Basic Information
        'name',
        'tagline',
        'description',
        'established_year',
        
        // Contact Information
        'address',
        'phone',
        'fax',
        'whatsapp',
        'email',
        'email_contact',
        'email_complaint',
        'email_whistleblowing',
        'website',
        
        // Visual Assets
        'logo',
        'logo_footer',
        'logo_footer_remove_bg',
        'logo_footer_opacity',
        'favicon',
        'profile_image',
        'organization_structure',
        
        // Company Profile
        'vision',
        'mission',
        'history',
        
        // Statistics
        'stat_years_experience',
        'stat_branch_offices',
        'stat_total_assets',
        'stat_cash_offices',
        'stat_mobile_cash_offices',
        'legacy_visitor_count',
        
        // Social Media
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'tiktok',
        
        // Regulatory Information
        'ojk_license',
        'ojk_tagline',
        'lps_tagline',
        'lps_guarantee_amount',
        
        // SEO & Footer
        'footer_description',
        'meta_description',
        'meta_keywords',
        
        // Operational Hours
        'operational_hours',
    ];

    protected $casts = [
        'established_year' => 'integer',
        'stat_years_experience' => 'integer',
        'stat_branch_offices' => 'integer',
        'stat_cash_offices' => 'integer',
        'stat_mobile_cash_offices' => 'integer',
        'legacy_visitor_count' => 'integer',
        'logo_footer_remove_bg' => 'boolean',
        'logo_footer_opacity' => 'integer',
        'operational_hours' => 'array',
    ];

    /**
     * Get cached company info
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

    /**
     * Get logo URL with fallback
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->logo ? Storage::url($this->logo) : null,
        );
    }

    /**
     * Get logo footer URL with fallback
     */
    protected function logoFooterUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->logo_footer ? Storage::url($this->logo_footer) : null,
        );
    }

    /**
     * Get favicon URL with fallback
     */
    protected function faviconUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->favicon ? Storage::url($this->favicon) : null,
        );
    }

    /**
     * Get profile image URL with fallback
     */
    protected function profileImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->profile_image ? Storage::url($this->profile_image) : null,
        );
    }

    /**
     * Get organization structure URL with fallback
     */
    protected function organizationStructureUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->organization_structure ? Storage::url($this->organization_structure) : null,
        );
    }

    /**
     * Get formatted operational hours
     */
    protected function formattedOperationalHours(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->operational_hours) {
                    return [];
                }

                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $dayNames = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa', 
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                    'saturday' => 'Sabtu',
                    'sunday' => 'Minggu'
                ];

                $formatted = [];
                foreach ($days as $day) {
                    if (isset($this->operational_hours[$day])) {
                        $dayData = $this->operational_hours[$day];
                        $formatted[$day] = [
                            'name' => $dayNames[$day],
                            'active' => $dayData['active'] ?? false,
                            'open' => $dayData['open'] ?? null,
                            'close' => $dayData['close'] ?? null,
                            'has_break' => $dayData['has_break'] ?? false,
                            'break_start' => $dayData['break_start'] ?? null,
                            'break_end' => $dayData['break_end'] ?? null,
                        ];
                    }
                }

                return $formatted;
            }
        );
    }

    /**
     * Get social media links
     */
    protected function socialMediaLinks(): Attribute
    {
        return Attribute::make(
            get: function () {
                return [
                    'facebook' => $this->facebook,
                    'instagram' => $this->instagram,
                    'twitter' => $this->twitter,
                    'youtube' => $this->youtube,
                    'linkedin' => $this->linkedin,
                    'tiktok' => $this->tiktok,
                ];
            }
        );
    }

    /**
     * Get company statistics
     */
    protected function statistics(): Attribute
    {
        return Attribute::make(
            get: function () {
                return [
                    'years_experience' => $this->stat_years_experience,
                    'branch_offices' => $this->stat_branch_offices,
                    'total_assets' => $this->stat_total_assets,
                    'cash_offices' => $this->stat_cash_offices,
                    'mobile_cash_offices' => $this->stat_mobile_cash_offices,
                    'legacy_visitor_count' => $this->legacy_visitor_count,
                ];
            }
        );
    }

    /**
     * Check if company has complete profile
     */
    protected function hasCompleteProfile(): Attribute
    {
        return Attribute::make(
            get: function () {
                $required = ['name', 'address', 'phone', 'email', 'description'];
                foreach ($required as $field) {
                    if (empty($this->$field)) {
                        return false;
                    }
                }
                return true;
            }
        );
    }
}