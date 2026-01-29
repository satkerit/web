<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    // Scopes
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
            ->where('blocked_until', '<', now());
    }

    public function scopePermanent($query)
    {
        return $query->where('is_permanent', true);
    }

    public function scopeTemporary($query)
    {
        return $query->where('is_permanent', false);
    }

    // Helpers
    public function isExpired(): bool
    {
        if ($this->is_permanent) {
            return false;
        }

        return $this->blocked_until && $this->blocked_until->isPast();
    }

    public function isActive(): bool
    {
        if ($this->is_permanent) {
            return true;
        }

        return $this->blocked_until && $this->blocked_until->isFuture();
    }

    public static function isBlocked(string $ip): bool
    {
        // Cache result for 5 minutes (300 seconds)
        // This drastically reduces DB load during DDoS attacks
        return Cache::remember('blocked_ip:' . $ip, 300, function () use ($ip) {
            return self::where('ip_address', $ip)
                ->where(function ($q) {
                    $q->where('is_permanent', true)
                        ->orWhere('blocked_until', '>', now());
                })
                ->exists();
        });
    }

    public static function blockIp(string $ip, string $reason = null, int $hours = 24, bool $permanent = false): self
    {
        $blocked = self::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'attempts' => \DB::raw('attempts + 1'),
                'blocked_until' => $permanent ? null : now()->addHours($hours),
                'is_permanent' => $permanent,
            ]
        );

        // Cache the blocked status immediately
        $ttl = $permanent ? 86400 : ($hours * 3600);
        Cache::put('blocked_ip:' . $ip, true, $ttl);

        return $blocked;
    }

    public static function unblockIp(string $ip): bool
    {
        // Clear cache immediately
        Cache::forget('blocked_ip:' . $ip);
        return self::where('ip_address', $ip)->delete();
    }

    public static function incrementAttempts(string $ip): int
    {
        $blocked = self::firstOrCreate(
            ['ip_address' => $ip],
            ['attempts' => 0]
        );

        $blocked->increment('attempts');

        return $blocked->attempts;
    }
}
