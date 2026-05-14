<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BoardMember extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Dewan/Direksi';
    }

    protected $fillable = [
        'name',
        'slug',
        'position',
        'type',
        'photo',
        'biography',
        'education',
        'experience',
        'order_position'
    ];

    protected $casts = [
        'education' => 'array',
        'experience' => 'array',
        'order_position' => 'integer'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function scopeKomisaris($query)
    {
        return $query->where('type', 'komisaris');
    }

    public function scopeDireksi($query)
    {
        return $query->where('type', 'direksi');
    }

    public function scopePengawasSyariah($query)
    {
        return $query->where('type', 'pengawas_syariah');
    }

    public function setBiographyAttribute($value)
    {
        $this->attributes['biography'] = \App\Helpers\HtmlSanitizer::clean($value);
    }

    protected static function booted(): void
    {
        $clearCache = function ($model) {
            Cache::forget("board_members_{$model->type}");
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }
}
