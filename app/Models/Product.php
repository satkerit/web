<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Produk';
    }

    protected $fillable = [
        'name',
        'slug',
        'type',
        'short_description',
        'description',
        'interest_rate',
        'features',
        'requirements',
        'benefits',
        'image',
        'image_alt',
        'brochure',
        'brochure_id',
        'is_active',
        'order_position'
    ];

    protected $casts = [
        'features' => 'array',
        'requirements' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'order_position' => 'integer',
        'brochure_id' => 'integer'
    ];

    public function libraryBrochure()
    {
        return $this->belongsTo(Brochure::class, 'brochure_id');
    }

    public function hasManualBrochure(): bool
    {
        return !empty($this->getAttribute('brochure'));
    }

    public function hasLibraryBrochure(): bool
    {
        return !empty($this->brochure_id) && $this->libraryBrochure !== null;
    }

    public function hasAnyBrochure(): bool
    {
        return $this->hasManualBrochure() || $this->hasLibraryBrochure();
    }

    public function getEffectiveBrochurePath(): ?string
    {
        if ($this->hasManualBrochure()) {
            return $this->getAttribute('brochure');
        }

        return $this->libraryBrochure?->file_path;
    }

    public function getEffectiveBrochureName(): ?string
    {
        if ($this->hasManualBrochure()) {
            return str_replace(' ', '_', $this->name) . '_Brosur.pdf';
        }

        return $this->libraryBrochure?->original_name;
    }

    public function getEffectiveBrochureDownloadUrl(): ?string
    {
        if (!$this->hasAnyBrochure()) {
            return null;
        }

        return route('brochures.download-product', $this);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the URL of the product image
     *
     * @return string URL lengkap ke gambar
     */
    public function getImageUrl()
    {
        if (!$this->image) {
            return asset('images/default-product.png');
        }

        return asset('storage/' . $this->image);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSimpananSyariah($query)
    {
        return $query->where('type', 'simpanan_syariah');
    }

    public function scopePembiayaanSyariah($query)
    {
        return $query->where('type', 'pembiayaan_syariah');
    }

    public function scopeDeposito($query)
    {
        return $query->where('type', 'deposito_syariah');
    }

    public function scopeDepositoSyariah($query)
    {
        return $query->where('type', 'deposito_syariah');
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setShortDescriptionAttribute($value)
    {
        $this->attributes['short_description'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    protected static function booted(): void
    {
        $clearCache = function () {
            Cache::forget('products_home_6');
            Cache::forget('products_simpanan_syariah');
            Cache::forget('products_pembiayaan_syariah');
            Cache::forget('products_deposito_syariah');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
