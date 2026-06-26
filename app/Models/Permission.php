<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;

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
        try {
            $query = static::query();

            // Only filter by is_active if column exists
            if (Schema::hasColumn((new static)->getTable(), 'is_active')) {
                $query->where('is_active', true);
            }

            return $query->orderBy('group')
                ->orderBy('display_name')
                ->get()
                ->groupBy('group');
        } catch (\Exception $e) {
            return collect();
        }
    }

    public static function getGroups()
    {
        try {
            $query = static::query();

            // Only filter by is_active if column exists
            if (Schema::hasColumn((new static)->getTable(), 'is_active')) {
                $query->where('is_active', true);
            }

            return $query->distinct()
                ->orderBy('group')
                ->pluck('group')
                ->filter()
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
