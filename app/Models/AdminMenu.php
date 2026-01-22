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
    public function canAccess(string $role): bool
    {
        $permission = $this->permissions()->where('role', $role)->first();
        return $permission ? $permission->can_access : false;
    }

    /**
     * Get all menus accessible by a role
     */
    public static function getMenusForRole(string $role): \Illuminate\Support\Collection
    {
        return Cache::remember("admin_menus_{$role}", 3600, function () use ($role) {
            return static::where('is_active', true)
                ->whereHas('permissions', function ($query) use ($role) {
                    $query->where('role', $role)->where('can_access', true);
                })
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get menus grouped by section for a role
     */
    public static function getGroupedMenusForRole(string $role): array
    {
        $menus = static::getMenusForRole($role);
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
            'auctions' => 'auctions.view',
            'reports' => 'reports.view',
            'company-info' => 'settings.company',
            'board-members' => 'board.manage',
            'offices' => 'offices.view',
            'kas-keliling' => 'offices.view',
            'careers' => 'careers.view',
            'customer-complaints' => 'complaints.view',
            'complaints' => 'complaints.view',
            'storage' => 'storage.view',
            'database-backup' => 'storage.manage',
            'settings' => 'settings.maintenance',
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
        foreach (User::getRoles() as $role => $name) {
            Cache::forget("admin_menus_{$role}");
        }
    }

    /**
     * Get all menus with permissions
     */
    public static function getAllWithPermissions(): \Illuminate\Support\Collection
    {
        return static::with('permissions')
            ->orderBy('section')
            ->orderBy('order')
            ->get();
    }
}
