<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordHistory extends Model
{
    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the user that owns the password history
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a password exists in history
     */
    public static function isPasswordReused(int $userId, string $newPassword, int $historyCount = 5): bool
    {
        $history = self::where('user_id', $userId)
            ->latest()
            ->take($historyCount)
            ->get();

        foreach ($history as $record) {
            if (\Hash::check($newPassword, $record->password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save password to history
     */
    public static function savePassword(int $userId, string $password): void
    {
        self::create([
            'user_id' => $userId,
            'password' => \Hash::make($password),
        ]);

        // Keep only last 5 passwords
        self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->skip(5)
            ->delete();
    }

    /**
     * Clean old password histories
     */
    public static function cleanup(int $daysToKeep = 365): int
    {
        return self::where('created_at', '<', now()->subDays($daysToKeep))->delete();
    }
}
