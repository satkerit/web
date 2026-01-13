<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Auction extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Lelang';
    }

    protected $fillable = [
        'title',
        'slug',
        'object_number',
        'description',
        'asset_type',
        'certificate_type',
        'certificate_number',
        'land_area',
        'building_area',
        'debtor_name',
        'location',
        'starting_price',
        'estimated_price',
        'auction_date',
        'registration_deadline',
        'auction_type',
        'auction_location',
        'deposit_amount',
        'deposit_percentage',
        'bank_account',
        'bank_name',
        'account_holder',
        'terms_conditions',
        'viewing_schedule',
        'kpknl_office',
        'risalah_number',
        'images',
        'documents',
        'status',
        'winning_bid',
        'winner_name',
        'sold_at',
        'contact_person',
        'contact_phone',
        'meta_description'
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'estimated_price' => 'decimal:2',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'winning_bid' => 'decimal:2',
        'auction_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'sold_at' => 'datetime',
        'images' => 'array',
        'documents' => 'array'
    ];

    // Jenis Sertifikat
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

    // Jenis Aset
    public static $assetTypes = [
        'tanah' => 'Tanah',
        'rumah' => 'Rumah Tinggal',
        'ruko' => 'Ruko/Rukan',
        'apartemen' => 'Apartemen',
        'gedung' => 'Gedung/Bangunan Komersial',
        'pabrik' => 'Pabrik/Gudang',
        'mobil' => 'Mobil',
        'motor' => 'Motor',
        'alat_berat' => 'Alat Berat',
        'mesin' => 'Mesin/Peralatan',
        'lainnya' => 'Lainnya'
    ];

    // Jenis Lelang
    public static $auctionTypes = [
        'eksekusi' => 'Lelang Eksekusi',
        'non_eksekusi_wajib' => 'Lelang Non-Eksekusi Wajib',
        'non_eksekusi_sukarela' => 'Lelang Non-Eksekusi Sukarela'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing']);
    }

    // Status Labels
    public static $statusLabels = [
        'upcoming' => 'Akan Datang',
        'ongoing' => 'Berlangsung',
        'closed' => 'Selesai',
        'sold' => 'Terjual',
        'cancelled' => 'Dibatalkan'
    ];

    // Status Colors for badges
    public static $statusColors = [
        'upcoming' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
        'ongoing' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
        'closed' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'dot' => 'bg-slate-500'],
        'sold' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500']
    ];

    // Get status label
    public function getStatusLabelAttribute()
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return self::$statusColors[$this->status] ?? self::$statusColors['closed'];
    }

    // Format harga ke Rupiah
    public function getFormattedStartingPriceAttribute()
    {
        return 'Rp ' . number_format($this->starting_price, 0, ',', '.');
    }

    public function getFormattedDepositAmountAttribute()
    {
        return $this->deposit_amount ? 'Rp ' . number_format($this->deposit_amount, 0, ',', '.') : null;
    }

    public function getFormattedWinningBidAttribute()
    {
        return $this->winning_bid ? 'Rp ' . number_format($this->winning_bid, 0, ',', '.') : null;
    }

    // Cek apakah masih bisa mendaftar
    public function getCanRegisterAttribute()
    {
        return $this->registration_deadline && $this->registration_deadline->isFuture() && !in_array($this->status, ['closed', 'sold', 'cancelled']);
    }

    // Hitung uang jaminan dari persentase jika tidak diisi manual
    public function getCalculatedDepositAttribute()
    {
        if ($this->deposit_amount) {
            return $this->deposit_amount;
        }

        if ($this->deposit_percentage && $this->starting_price) {
            return ($this->deposit_percentage / 100) * $this->starting_price;
        }

        return null;
    }

    protected static function booted(): void
    {
        $clearCache = fn() => \Illuminate\Support\Facades\Cache::forget('auctions_home_3');
        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
