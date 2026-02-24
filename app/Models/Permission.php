<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Permission';
    }

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }

    public static function getGroupedPermissions()
    {
        return static::where('is_active', true)
            ->orderBy('group')
            ->orderBy('display_name')
            ->get()
            ->groupBy('group');
    }

    public static function getGroups()
    {
        return static::where('is_active', true)
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->filter()
            ->values()
            ->toArray();
    }
}
