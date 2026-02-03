<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;

class Auction extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Lelang';
    }

    protected $fillable = [
        // Basic Information
        'title',
        'slug',
        'description',
        'auction_number',
        'object_number',

        // Asset Information
        'asset_type',
        'asset_category',
        'asset_description',

        // Certificate Information
        'certificate_type',
        'certificate_number',
        'certificate_date',
        'certificate_issued_by',

        // Property Details
        'land_area',
        'building_area',
        'building_condition',
        'floors',
        'bedrooms',
        'bathrooms',
        'parking_spaces',
        'year_built',

        // Location Details
        'address',
        'village',
        'district',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',

        // Debtor Information
        'debtor_name',
        'debtor_id_number',
        'debtor_address',

        // Auction Information
        'auction_type',
        'auction_method',
        'auction_date',
        'auction_time',
        'auction_location',
        'auction_address',
        'auction_url',

        // Registration
        'registration_start',
        'registration_end',
        'registration_requirements',
        'registration_procedure',

        // Pricing
        'limit_price',
        'estimated_price',
        'deposit_amount',
        'deposit_percentage',
        'increment_amount',

        // Bank Information
        'bank_name',
        'bank_branch',
        'account_number',
        'account_holder',
        'swift_code',

        // Legal Information
        'creditor_name',
        'creditor_address',
        'legal_basis',
        'court_decision',
        'court_decision_date',
        'debt_amount',
        'encumbrance_details',

        // Viewing Information
        'viewing_start',
        'viewing_end',
        'viewing_schedule',
        'viewing_contact',
        'viewing_notes',

        // Terms & Conditions
        'terms_conditions',
        'special_conditions',
        'payment_terms',
        'payment_deadline_days',
        'delivery_terms',

        // Organizer Information
        'organizer_name',
        'organizer_type',
        'organizer_address',
        'organizer_phone',
        'organizer_email',
        'organizer_website',

        // Contact Information
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'contact_whatsapp',
        'contact_office_hours',
        'contacts',

        // Documents & Media
        'images',
        'documents',
        'floor_plans',
        'certificates',
        'virtual_tour_url',
        'video_url',

        // Status & Results
        'status',
        'status_notes',

        // Auction Results
        'winning_bid',
        'winner_name',
        'winner_id_number',
        'winner_address',
        'winner_phone',
        'sold_at',
        'auction_notes',
        'total_bidders',
        'total_bids',

        // Additional Information
        'facilities',
        'nearby_facilities',
        'transportation_access',
        'investment_potential',
        'market_analysis',
        'risk_factors',

        // SEO & Meta
        'meta_title',
        'meta_description',
        'meta_keywords',

        // Tracking
        'view_count',
        'interest_count',
        'download_count',

        // Publishing
        'published_at',
        'featured_until',
        'is_featured',
        'is_urgent',
        'sort_order'
    ];

    protected $casts = [
        'auction_date' => 'datetime',
        'auction_time' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'viewing_start' => 'datetime',
        'viewing_end' => 'datetime',
        'certificate_date' => 'date',
        'court_decision_date' => 'date',
        'sold_at' => 'datetime',
        'published_at' => 'datetime',
        'featured_until' => 'datetime',
        'limit_price' => 'decimal:2',
        'estimated_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'increment_amount' => 'decimal:2',
        'debt_amount' => 'decimal:2',
        'winning_bid' => 'decimal:2',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'images' => 'array',
        'documents' => 'array',
        'floor_plans' => 'array',
        'certificates' => 'array',
        'contacts' => 'array',
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'view_count' => 'integer',
        'interest_count' => 'integer',
        'download_count' => 'integer',
        'total_bidders' => 'integer',
        'total_bids' => 'integer',
        'floors' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'parking_spaces' => 'integer',
        'year_built' => 'integer',
        'payment_deadline_days' => 'integer',
        'sort_order' => 'integer'
    ];

    // Constants
    public static $assetTypes = [
        'tanah' => 'Tanah',
        'rumah' => 'Rumah Tinggal',
        'ruko' => 'Ruko/Rukan',
        'apartemen' => 'Apartemen/Kondominium',
        'gedung' => 'Gedung Komersial',
        'pabrik' => 'Pabrik/Gudang',
        'kendaraan' => 'Kendaraan',
        'mesin' => 'Mesin/Peralatan',
        'lainnya' => 'Lainnya'
    ];

    public static $certificateTypes = [
        'SHM' => 'Sertifikat Hak Milik (SHM)',
        'SHGB' => 'Sertifikat Hak Guna Bangunan (SHGB)',
        'SHP' => 'Sertifikat Hak Pakai (SHP)',
        'AJB' => 'Akta Jual Beli (AJB)',
        'PPJB' => 'Perjanjian Pengikatan Jual Beli (PPJB)',
        'Girik' => 'Girik/Letter C',
        'BPKB' => 'BPKB (Kendaraan)',
        'Lainnya' => 'Lainnya'
    ];

    public static $auctionTypes = [
        'eksekusi_hak_tanggungan' => 'Eksekusi Hak Tanggungan',
        'eksekusi_fidusia' => 'Eksekusi Fidusia',
        'eksekusi_hipotik' => 'Eksekusi Hipotik',
        'non_eksekusi_wajib' => 'Non-Eksekusi Wajib',
        'non_eksekusi_sukarela' => 'Non-Eksekusi Sukarela'
    ];

    public static $statusLabels = [
        'draft' => 'Draft',
        'published' => 'Dipublikasi',
        'registration_open' => 'Pendaftaran Dibuka',
        'registration_closed' => 'Pendaftaran Ditutup',
        'auction_scheduled' => 'Lelang Terjadwal',
        'auction_ongoing' => 'Lelang Berlangsung',
        'auction_completed' => 'Lelang Selesai',
        'sold' => 'Terjual',
        'unsold' => 'Tidak Terjual',
        'cancelled' => 'Dibatalkan',
        'postponed' => 'Ditunda'
    ];

    public static $statusColors = [
        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500'],
        'published' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
        'registration_open' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
        'registration_closed' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500'],
        'auction_scheduled' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'dot' => 'bg-purple-500'],
        'auction_ongoing' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
        'auction_completed' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'dot' => 'bg-indigo-500'],
        'sold' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
        'unsold' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'dot' => 'bg-slate-500'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
        'postponed' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500']
    ];

    public static function statuses(): array
    {
        return array_keys(self::$statusLabels);
    }

    // Slug configuration
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', '!=', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            'published',
            'registration_open',
            'registration_closed',
            'auction_scheduled',
            'auction_ongoing'
        ]);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)
            ->where(function ($q) {
                $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            });
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('auction_date', '>', now())
            ->whereIn('status', ['published', 'registration_open', 'registration_closed', 'auction_scheduled']);
    }

    public function scopeByAssetType(Builder $query, string $type): Builder
    {
        return $query->where('asset_type', $type);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopePriceRange(Builder $query, $min = null, $max = null): Builder
    {
        if ($min) {
            $query->where('limit_price', '>=', $min);
        }
        if ($max) {
            $query->where('limit_price', '<=', $max);
        }
        return $query;
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? ($this->status ?? 'Tidak Diketahui');
    }

    public function getStatusColorAttribute(): array
    {
        return self::$statusColors[$this->status] ?? self::$statusColors['draft'];
    }

    public function getAssetTypeLabelAttribute(): string
    {
        return self::$assetTypes[$this->asset_type] ?? ($this->asset_type ?? 'Tidak Diketahui');
    }

    public function getCertificateTypeLabelAttribute(): ?string
    {
        return $this->certificate_type ? (self::$certificateTypes[$this->certificate_type] ?? $this->certificate_type) : null;
    }

    public function getAuctionTypeLabelAttribute(): string
    {
        return self::$auctionTypes[$this->auction_type] ?? ($this->auction_type ?? 'Tidak Diketahui');
    }

    public function getFormattedLimitPriceAttribute(): string
    {
        if (!$this->limit_price) {
            return 'Hubungi Kami';
        }
        return 'Rp ' . number_format($this->limit_price, 0, ',', '.');
    }

    public function getFormattedEstimatedPriceAttribute(): ?string
    {
        return $this->estimated_price ? 'Rp ' . number_format($this->estimated_price, 0, ',', '.') : null;
    }

    public function getFormattedDepositAmountAttribute(): ?string
    {
        return $this->deposit_amount ? 'Rp ' . number_format($this->deposit_amount, 0, ',', '.') : null;
    }

    // Legacy accessors for backward compatibility
    public function getLocationAttribute(): ?string
    {
        return $this->city;
    }

    public function getStartingPriceAttribute(): ?float
    {
        return $this->limit_price;
    }

    public function getFormattedStartingPriceAttribute(): string
    {
        return $this->formatted_limit_price;
    }

    public function getCalculatedDepositAttribute(): ?float
    {
        if ($this->deposit_amount) {
            return $this->deposit_amount;
        }

        if ($this->deposit_percentage && $this->limit_price) {
            return ($this->deposit_percentage / 100) * $this->limit_price;
        }

        return null;
    }

    public function getFormattedCalculatedDepositAttribute(): ?string
    {
        $deposit = $this->calculated_deposit;
        return $deposit ? 'Rp ' . number_format($deposit, 0, ',', '.') : null;
    }

    public function getIsRegistrationOpenAttribute(): bool
    {
        if (!$this->registration_start || !$this->registration_end) {
            return false;
        }

        $now = now();
        return $now >= $this->registration_start && $now <= $this->registration_end;
    }

    public function getIsViewingAvailableAttribute(): bool
    {
        if (!$this->viewing_start || !$this->viewing_end) {
            return false;
        }

        $now = now();
        return $now >= $this->viewing_start && $now <= $this->viewing_end;
    }

    public function getDaysUntilAuctionAttribute(): int
    {
        return now()->diffInDays($this->auction_date, false);
    }

    public function getTimeUntilAuctionAttribute(): string
    {
        if (!$this->auction_date) {
            return 'Tanggal belum ditentukan';
        }

        $diff = now()->diff($this->auction_date);

        if ($diff->days > 0) {
            return $diff->days . ' hari lagi';
        } elseif ($diff->h > 0) {
            return $diff->h . ' jam lagi';
        } elseif ($diff->i > 0) {
            return $diff->i . ' menit lagi';
        } else {
            return 'Segera dimulai';
        }
    }

    public function getMainImageAttribute(): ?string
    {
        if ($this->images && count($this->images) > 0) {
            return \App\Helpers\StorageHelper::url($this->images[0]);
        }
        return null;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->village,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code
        ]);

        return implode(', ', $parts);
    }

    // Methods
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementInterestCount(): void
    {
        $this->increment('interest_count');
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function markAsSold(float $winningBid, string $winnerName, array $additionalData = []): void
    {
        $this->update(array_merge([
            'status' => 'sold',
            'winning_bid' => $winningBid,
            'winner_name' => $winnerName,
            'sold_at' => now()
        ], $additionalData));
    }

    public function markAsUnsold(string $notes = null): void
    {
        $this->update([
            'status' => 'unsold',
            'auction_notes' => $notes
        ]);
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'status_notes' => $reason
        ]);
    }

    public function postpone(Carbon $newDate, string $reason = null): void
    {
        $this->update([
            'status' => 'postponed',
            'auction_date' => $newDate,
            'status_notes' => $reason
        ]);
    }

    // Cache clearing
    protected static function booted(): void
    {
        $clearCache = fn() => \Illuminate\Support\Facades\Cache::forget('auctions_home_featured');
        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
