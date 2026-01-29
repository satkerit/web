<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    protected static function getAuditModelName(): string
    {
        return 'User';
    }

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_EDITOR = 'editor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role model relationship
     */
    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        // 1. Prioritize Check from Role model relationship if exists
        if ($this->role_id && $this->relationLoaded('roleModel') ? $this->roleModel : $this->roleModel()->first()) {
            return $this->roleModel->hasPermission($permission);
        }

        // 2. Fallback: Check from AdminMenuPermission based on role string (Legacy Support)
        return $this->hasMenuPermission($permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Super admin has all permissions
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        // 1. Prioritize Check from Role model relationship if exists
        if ($this->role_id && $this->relationLoaded('roleModel') ? $this->roleModel : $this->roleModel()->first()) {
            return $this->roleModel->hasAnyPermission($permissions);
        }

        // 2. Fallback: Check from AdminMenuPermission
        foreach ($permissions as $permission) {
            if ($this->hasMenuPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check permission from AdminMenuPermission (legacy system)
     * Maps permission names to menu keys
     */
    protected function hasMenuPermission(string $permission): bool
    {
        // Map permission to menu key
        $menuKey = $this->permissionToMenuKey($permission);

        if (!$menuKey) {
            // If no mapping found, allow access for admin/editor for general permissions
            return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_EDITOR]);
        }

        $menu = \App\Models\AdminMenu::where('key', $menuKey)->first();

        if (!$menu) {
            return false;
        }

        return $menu->canAccess($this->role);
    }

    /**
     * Map permission name to menu key
     */
    protected function permissionToMenuKey(string $permission): ?string
    {
        $map = [
            'dashboard.view' => 'dashboard',
            'news.view' => 'news',
            'news.create' => 'news',
            'news.edit' => 'news',
            'news.delete' => 'news',
            'products.view' => 'products',
            'products.create' => 'products',
            'products.edit' => 'products',
            'products.delete' => 'products',
            'auctions.view' => 'auctions',
            'auctions.create' => 'auctions',
            'auctions.edit' => 'auctions',
            'auctions.delete' => 'auctions',
            'reports.view' => 'reports',
            'reports.create' => 'reports',
            'reports.edit' => 'reports',
            'reports.delete' => 'reports',
            'offices.view' => 'offices',
            'offices.create' => 'offices',
            'offices.edit' => 'offices',
            'offices.delete' => 'offices',
            'careers.view' => 'careers',
            'careers.create' => 'careers',
            'careers.edit' => 'careers',
            'careers.delete' => 'careers',
            'board.manage' => 'board-members',
            'complaints.view' => 'complaints',
            'complaints.manage' => 'complaints',
            'storage.view' => 'storage',
            'storage.manage' => 'storage',
            'settings.hero' => 'hero-slides',
            'settings.company' => 'company-info',
            'settings.email' => 'settings',
            'settings.maintenance' => 'settings',
            'settings.financing' => 'financing-config',
            'settings.menu' => 'menu-permissions',
            'users.view' => 'users',
            'users.create' => 'users',
            'users.edit' => 'users',
            'users.delete' => 'users',
            'roles.view' => 'roles',
            'roles.create' => 'roles',
            'roles.edit' => 'roles',
            'roles.delete' => 'roles',
            'audit.view' => 'audit-trails',
            'visitors.view' => 'visitor-stats',
        ];

        return $map[$permission] ?? null;
    }

    // RBAC Methods (backward compatibility)
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermission('users.view') || $this->isSuperAdmin();
    }

    public function canManageSettings(): bool
    {
        return $this->hasAnyPermission(['settings.company', 'settings.email', 'settings.maintenance']) || $this->isAdmin();
    }

    public function canManageContent(): bool
    {
        return $this->hasAnyPermission(['news.view', 'products.view', 'auctions.view']) ||
            in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_EDITOR]);
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_EDITOR => 'Editor',
        ];
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return $this->roleModel?->display_name ?? ucfirst(str_replace('_', ' ', $this->role));
    }
}
