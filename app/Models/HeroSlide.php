<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasResponsiveImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HeroSlide extends Model
{
    use HasFactory, HasResponsiveImage, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Hero Slide';
    }

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link_url',
        'link_text',
        'is_active',
        'order_position',
        'transition_type',
        'transition_duration',
        'show_title',
        'show_subtitle',
        'show_button'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_position' => 'integer',
        'transition_duration' => 'integer',
        'show_title' => 'boolean',
        'show_subtitle' => 'boolean',
        'show_button' => 'boolean'
    ];

    /**
     * Available transition types
     */
    public static function getTransitionTypes(): array
    {
        return [
            'slide' => 'Slide (Geser)',
            'fade' => 'Fade (Pudar)',
            'zoom' => 'Zoom (Perbesar)',
            'flip' => 'Flip (Balik)',
            'cube' => 'Cube (Kubus)',
            'cards' => 'Cards (Kartu)',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_position');
    }

    protected static function booted(): void
    {
        $clearCache = function () {
            // Clear all possible hero slide caches (1-20)
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget("hero_slides_{$i}");
            }
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
