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
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check from Role model relationship
        if ($this->role_id && $this->roleModel) {
            return $this->roleModel->hasPermission($permission);
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check from Role model relationship
        if ($this->role_id && $this->roleModel) {
            return $this->roleModel->hasAnyPermission($permissions);
        }

        return false;
    }

    // RBAC Methods
    public function isSuperAdmin(): bool
    {
        return $this->roleModel?->name === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->roleModel?->name, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    public function isEditor(): bool
    {
        return $this->roleModel?->name === self::ROLE_EDITOR;
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->roleModel?->name, $roles);
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
            in_array($this->roleModel?->name, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_EDITOR]);
    }

    /**
     * Get role name
     */
    public function getRoleName(): string
    {
        return $this->roleModel?->name ?? 'editor';
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return $this->roleModel?->display_name ?? 'Editor';
    }

    /**
     * Get available roles (for backward compatibility)
     * @deprecated Use Role model directly
     */
    public static function getRoles(): array
    {
        return Role::orderBy('name')->pluck('display_name', 'name')->toArray();
    }
}
