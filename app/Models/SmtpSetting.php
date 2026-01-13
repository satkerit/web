<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

class SmtpSetting extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Pengaturan SMTP';
    }

    protected static function getAuditIdentifier(Model $model): string
    {
        return $model->mail_host ?? 'SMTP Settings';
    }

    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'mail_password',
    ];

    /**
     * Set encrypted password
     */
    public function setMailPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['mail_password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Get decrypted password
     */
    public function getDecryptedPassword(): ?string
    {
        if ($this->attributes['mail_password'] ?? null) {
            try {
                return Crypt::decryptString($this->attributes['mail_password']);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Get active SMTP settings
     */
    public static function getActive(): ?self
    {
        return Cache::remember('smtp_settings', 3600, function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Apply SMTP settings to Laravel config
     */
    public function applyToConfig(): void
    {
        Config::set('mail.default', $this->mail_mailer);
        Config::set('mail.mailers.smtp.host', $this->mail_host);
        Config::set('mail.mailers.smtp.port', $this->mail_port);
        Config::set('mail.mailers.smtp.username', $this->mail_username);
        Config::set('mail.mailers.smtp.password', $this->getDecryptedPassword());
        Config::set('mail.mailers.smtp.encryption', $this->mail_encryption);
        Config::set('mail.from.address', $this->mail_from_address);
        Config::set('mail.from.name', $this->mail_from_name);
    }

    /**
     * Clear cache
     */
    public static function clearCache(): void
    {
        Cache::forget('smtp_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }
}
