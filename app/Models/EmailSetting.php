<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class EmailSetting extends Model
{
    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'reply_to_address',
        'reply_to_name',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'port' => 'integer',
    ];

    /**
     * Encrypt password before storing
     */
    public function setPasswordAttribute($value): void
    {
        if (empty($value)) {
            return;
        }

        $this->attributes['password'] = Crypt::encryptString($value);
    }

    /**
     * Get cached settings
     */
    public static function getSettings(): ?self
    {
        return Cache::remember('email_settings', 3600, function () {
            return self::first();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('email_settings');
    }

    /**
     * Get decrypted password
     */
    public function getDecryptedPassword(): ?string
    {
        if (empty($this->password)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->password);
        } catch (\Exception $e) {
            return $this->password;
        }
    }

    /**
     * Check if password is set
     */
    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    /**
     * Boot method
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
