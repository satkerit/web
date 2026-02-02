<?php

namespace App\Traits;

trait AuthorizesAdminActions
{
    /**
     * Check if user can view content
     */
    protected function authorizeView(string $permission): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        // Admin role has access to most settings including company info
        if ($user->isAdmin() && str_starts_with($permission, 'settings.')) {
            return;
        }

        if (!$user->hasPermission($permission)) {
            if (request()->expectsJson()) {
                abort(403, 'Anda tidak memiliki akses untuk melihat halaman ini.');
            }
            abort(403, 'Anda tidak memiliki akses untuk melihat halaman ini.');
        }
    }

    /**
     * Check if user can create content
     */
    protected function authorizeCreate(string $permission): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }
    }

    /**
     * Check if user can edit content
     */
    protected function authorizeEdit(string $permission): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        // Admin role has access to most settings including company info
        if ($user->isAdmin() && str_starts_with($permission, 'settings.')) {
            return;
        }

        if (!$user->hasPermission($permission)) {
            if (request()->expectsJson()) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit data.');
            }
            abort(403, 'Anda tidak memiliki akses untuk mengedit data.');
        }
    }

    /**
     * Check if user can delete content
     */
    protected function authorizeDelete(string $permission): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        // Admin role has access to most settings including company info
        if ($user->isAdmin() && str_starts_with($permission, 'settings.')) {
            return;
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }
    }

    /**
     * Check if user has any of the given permissions
     */
    protected function authorizeAny(array $permissions): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        if (!$user->hasAnyPermission($permissions)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /**
     * Check if user is at least admin
     */
    protected function authorizeAdmin(): void
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /**
     * Check if user is super admin
     */
    protected function authorizeSuperAdmin(): void
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }
    }
}
