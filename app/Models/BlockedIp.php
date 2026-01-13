<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BlockedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'reason',
        'attempts',
        'blocked_until',
        'is_permanent',
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
        'is_permanent' => 'boolean',
    ];

    public static function isBlocked(string $ip): bool
    {
        return Cache::remember("blocked_ip:{$ip}", 300, function () use ($ip) {
            $blocked = self::where('ip_address', $ip)
                ->where(function ($query) {
                    $query->where('is_permanent', true)
                        ->orWhere('blocked_until', '>', now());
                })
                ->exists();

            return $blocked;
        });
    }

    public static function blockIp(string $ip, string $reason, int $hours = 24, bool $permanent = false): self
    {
        $blocked = self::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'blocked_until' => $permanent ? null : now()->addHours($hours),
                'is_permanent' => $permanent,
                'attempts' => DB::raw('attempts + 1'),
            ]
        );

        Cache::forget("blocked_ip:{$ip}");

        return $blocked;
    }

    public static function unblockIp(string $ip): bool
    {
        $result = self::where('ip_address', $ip)->delete();
        Cache::forget("blocked_ip:{$ip}");

        return $result > 0;
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_permanent', true)
                ->orWhere('blocked_until', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->where('is_permanent', false)
            ->where('blocked_until', '<=', now());
    }
}
