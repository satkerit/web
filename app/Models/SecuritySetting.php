<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SecuritySetting extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaturan Keamanan';
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        return 'Security Settings';
    }

    protected $fillable = [
        'rate_limit_web',
        'rate_limit_admin',
        'rate_limit_login',
        'rate_limit_password_reset',
        'rate_limit_download',
        'block_threshold',
        'block_duration_hours',
        'ip_whitelist',
        'ip_blacklist',
        'enable_suspicious_blocking',
        'enable_rate_limiting',
        'log_security_events',
        'session_lifetime',
        'idle_timeout',
        'idle_warning',
        'auto_extend_session',
        'enable_session_tracking',
    ];

    protected $casts = [
        'enable_suspicious_blocking' => 'boolean',
        'enable_rate_limiting' => 'boolean',
        'log_security_events' => 'boolean',
        'auto_extend_session' => 'boolean',
        'enable_session_tracking' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return Cache::remember('security_settings', 3600, function () {
            try {
                if (!Schema::hasTable('security_settings')) {
                    return new self();
                }
                return self::first() ?? self::create([]);
            } catch (\Exception $e) {
                return new self();
            }
        });
    }

    public function getWhitelistArray(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->ip_whitelist ?? '')));
    }

    public function getBlacklistArray(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->ip_blacklist ?? '')));
    }

    public static function clearCache(): void
    {
        Cache::forget('security_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
    }
}
