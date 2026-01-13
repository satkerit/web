<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class News extends Model
{
    use HasFactory, HasSlug, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Berita';
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'is_published',
        'published_at',
        'author_id',
        'author'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class)->orderBy('order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('news_home_3'));
        static::deleted(fn() => Cache::forget('news_home_3'));
    }
}
