<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Career extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Karir';
    }

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'salary_range',
        'deadline',
        'is_active',
        'order_position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline' => 'date',
        'order_position' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('deadline')
                ->orWhere('deadline', '>=', now()->toDateString());
        });
    }

    public function scopeAvailable($query)
    {
        return $query->active()->notExpired();
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match ($this->employment_type) {
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Kontrak',
            'internship' => 'Magang',
            default => $this->employment_type,
        };
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setRequirementsAttribute($value)
    {
        $this->attributes['requirements'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setResponsibilitiesAttribute($value)
    {
        $this->attributes['responsibilities'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    public function setBenefitsAttribute($value)
    {
        $this->attributes['benefits'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('careers_active'));
        static::deleted(fn() => Cache::forget('careers_active'));
    }
}
