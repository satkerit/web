<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KasKelilingSchedule extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Kas Keliling Schedule';
    }

    protected static function getAuditIdentifier(\Illuminate\Database\Eloquent\Model $model): string
    {
        return $model->location . ' - ' . $model->schedule_date->format('d/m/Y');
    }

    protected $table = 'kas_keliling_schedules';

    protected $fillable = [
        'kas_keliling_id',
        'schedule_date',
        'day_name',
        'start_time',
        'end_time',
        'location',
        'facility',
        'pic_name',
        'pic_phone',
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

    public function kasKeliling()
    {
        return $this->belongsTo(KasKeliling::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('schedule_date', '>=', now()->toDateString());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('schedule_date', now()->toDateString());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('schedule_date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ]);
    }

    // Accessors
    public function getIsTodayAttribute(): bool
    {
        return $this->schedule_date->isToday();
    }

    public function getIsTomorrowAttribute(): bool
    {
        return $this->schedule_date->isTomorrow();
    }

    public function getTimeRangeAttribute(): string
    {
        return \Carbon\Carbon::parse($this->start_time)->format('H:i') . ' - ' .
            \Carbon\Carbon::parse($this->end_time)->format('H:i');
    }

    public function getFacilityListAttribute(): array
    {
        if (empty($this->facility)) {
            return [];
        }
        return array_map('trim', explode(',', $this->facility));
    }

    // Cache management
    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('kas_keliling_schedules'));
        static::deleted(fn() => Cache::forget('kas_keliling_schedules'));
    }
}
