<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class KasKelilingSchedule extends Model
{
    protected $fillable = [
        'kas_keliling_id',
        'schedule_date',
        'day_name',
        'start_time',
        'end_time',
        'location',
        'route',
        'services_offered',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'route' => 'array',
        'services_offered' => 'array',
        'is_active' => 'boolean'
    ];

    public function kasKeliling(): BelongsTo
    {
        return $this->belongsTo(KasKeliling::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('schedule_date', '>=', now()->toDateString());
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('kas_keliling'));
        static::deleted(fn() => Cache::forget('kas_keliling'));
    }
}
