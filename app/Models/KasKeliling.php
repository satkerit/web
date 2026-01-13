<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class KasKeliling extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Kas Keliling';
    }

    protected static function getAuditIdentifier(\Illuminate\Database\Eloquent\Model $model): string
    {
        return $model->area_name ?? "ID: {$model->id}";
    }

    protected $table = 'kas_keliling';

    protected $fillable = [
        'area_name',
        'schedule_date',
        'day_name',
        'schedule',
        'route',
        'contact_person',
        'contact_phone',
        'services_offered',
        'operational_hours',
        'is_active'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule' => 'array',
        'route' => 'array',
        'services_offered' => 'array',
        'operational_hours' => 'array',
        'is_active' => 'boolean'
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(KasKelilingSchedule::class);
    }

    public function upcomingSchedules(): HasMany
    {
        return $this->hasMany(KasKelilingSchedule::class)
            ->where('schedule_date', '>=', now()->toDateString())
            ->where('is_active', true)
            ->orderBy('schedule_date');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('kas_keliling'));
        static::deleted(fn() => Cache::forget('kas_keliling'));
    }
}
