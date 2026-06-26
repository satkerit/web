<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class AdminMenu extends Model
{
    protected $fillable = [
        'key',
        'name',
        'route',
        'icon',
        'section',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(AdminMenuPermission::class);
    }

    /**
     * Check if a role can access this menu
     */
    public function canAccessByRoleId(int $roleId): bool
    {
        $permission = $this->permissions()->where('role_id', $roleId)->first();
        return $permission ? $permission->can_access : false;
    }

    /**
     * Check if a role can access this menu (legacy method for backward compatibility)
     */
    public function canAccess(string $role): bool
    {
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            return false;
        }
        return $this->canAccessByRoleId($roleModel->id);
    }

    /**
     * Get all menus accessible by a role ID
     */
    public static function getMenusForRoleId(int $roleId): \Illuminate\Support\Collection
    {
        return Cache::remember("admin_menus_role_{$roleId}", 3600, function () use ($roleId) {
            return static::where('is_active', true)
                ->whereHas('permissions', function ($query) use ($roleId) {
                    $query->where('role_id', $roleId)->where('can_access', true);
                })
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get all menus accessible by a role (legacy method)
     */
    public static function getMenusForRole(string $role): \Illuminate\Support\Collection
    {
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            return collect();
        }
        return static::getMenusForRoleId($roleModel->id);
    }

    /**
     * Get menus grouped by section for a role ID
     */
    public static function getGroupedMenusForRoleId(int $roleId): array
    {
        $menus = static::getMenusForRoleId($roleId);
        $grouped = [];

        foreach ($menus as $menu) {
            $section = $menu->section ?? 'default';
            if (!isset($grouped[$section])) {
                $grouped[$section] = [];
            }
            $grouped[$section][] = $menu;
        }

        return $grouped;
    }

    /**
     * Get menus grouped by section for a role (legacy method)
     */
    public static function getGroupedMenusForRole(string $role): array
    {
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            return [];
        }
        return static::getGroupedMenusForRoleId($roleModel->id);
    }

    /**
     * Get menus for a user based on their Role model permissions
     */
    public static function getMenusForUser($user): \Illuminate\Support\Collection
    {
        // Menu key to permission mapping
        $menuPermissionMap = [
            'dashboard' => 'dashboard.view',
            'hero-slides' => 'settings.hero',
            'why-choose-us' => 'settings.hero',
            'news' => 'news.view',
            'products' => 'products.view',
            'brochures' => 'products.view',
            'auctions' => 'auctions.view',
            'reports' => 'reports.view',
            'company-info' => 'settings.company',
            'board-members' => 'board.manage',
            'offices' => 'offices.view',
            'kas-keliling' => 'offices.view',
            'careers' => 'careers.view',
            'customer-complaints' => 'complaints.view',
            'complaints' => 'complaints.view',
            'complaint-settings' => 'settings.complaints',
            'storage' => 'storage.view',
            'database-backup' => 'storage.manage',
            'settings' => 'settings.maintenance',
            'site-settings' => 'settings.site',
            'composer-update' => 'settings.composer',
            'security-settings' => 'settings.security',
            'email-settings' => 'settings.email',
            'financing-config' => 'settings.financing',
            'audit-trails' => 'audit.view',
            'visitor-stats' => 'audit.visitors',
            'menu-permissions' => 'settings.menu',
            'roles' => 'roles.view',
            'users' => 'users.view',
        ];

        $allMenus = static::where('is_active', true)->orderBy('order')->get();

        return $allMenus->filter(function ($menu) use ($user, $menuPermissionMap) {
            // Dashboard is always accessible
            if ($menu->key === 'dashboard') {
                return true;
            }

            $permission = $menuPermissionMap[$menu->key] ?? null;

            if (!$permission) {
                return false;
            }

            return $user->hasPermission($permission);
        });
    }

    /**
     * Get menus grouped by section for a user
     */
    public static function getGroupedMenusForUser($user): array
    {
        $menus = static::getMenusForUser($user);
        $grouped = [];

        foreach ($menus as $menu) {
            $section = $menu->section ?? 'default';
            if (!isset($grouped[$section])) {
                $grouped[$section] = [];
            }
            $grouped[$section][] = $menu;
        }

        return $grouped;
    }

    /**
     * Clear menu cache for all roles
     */
    public static function clearCache(): void
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            Cache::forget("admin_menus_{$role->name}");
            Cache::forget("admin_menus_role_{$role->id}");
        }
    }

    /**
     * Get all menus with permissions
     */
    public static function getAllWithPermissions(): \Illuminate\Support\Collection
    {
        return static::with(['permissions' => function ($query) {
            $query->with('role');
        }])
            ->orderBy('section')
            ->orderBy('order')
            ->get();
    }
}
