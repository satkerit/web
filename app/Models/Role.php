<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use Auditable;

    protected static function getAuditModelName(): string
    {
        return 'Role';
    }

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    public function hasPermission(string $permission): bool
    {
        return Cache::remember("role_{$this->id}_permission_{$permission}", 3600, function () use ($permission) {
            return $this->permissions()->where('name', $permission)->exists();
        });
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
        $this->clearPermissionCache();
    }

    public function clearPermissionCache(): void
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            Cache::forget("role_{$this->id}_permission_{$permission->name}");
        }
    }

    public static function clearAllCache(): void
    {
        $roles = static::all();
        $permissions = Permission::all();

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                Cache::forget("role_{$role->id}_permission_{$permission->name}");
            }
        }
    }

    public static function getActiveRoles()
    {
        return static::where('is_active', true)->orderBy('display_name')->get();
    }
}
