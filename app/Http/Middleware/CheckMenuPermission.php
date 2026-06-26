<?php

namespace App\Http\Middleware;

use App\Models\AdminMenu;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Routes that are always accessible to authenticated users
     */
    protected array $alwaysAccessibleRoutes = [
        'admin.dashboard',
        'admin.profile',
        'admin.profile.edit',
        'admin.profile.update',
        'admin.profile.password',
        'admin.storage.api.browse',
        'admin.storage.upload-editor-image',
        'session.extend',
        'session.status',
        'admin.composer-update.index',
        'admin.composer-update.run',
    ];

    /**
     * Routes that require super admin access only
     */
    protected array $superAdminOnlyRoutes = [
        'admin.menu-permissions',
        'admin.roles',
        'admin.users',
    ];

    /**
     * Route to menu key mapping for permission checking
     * null = accessible to all authenticated users
     * string = requires specific menu permission
     */
    protected array $routeMenuMap = [
        // Always accessible
        'admin.dashboard' => null,
        'admin.profile' => null,

        // Content Management (accessible to admin and editor roles)
        'admin.hero-slides' => 'hero-slides',
        'admin.news' => 'news',
        'admin.products' => 'products',
        'admin.auctions' => 'auctions',
        'admin.reports' => 'reports',
        'admin.board-members' => 'board-members',
        'admin.offices' => 'offices',
        'admin.careers' => 'careers',
        'admin.brochures' => 'brochures',
        'admin.why-choose-us' => 'why-choose-us',
        'admin.kas-keliling' => 'kas-keliling',

        // Settings (accessible to admin roles)
        'admin.company-info.edit' => 'company-info',
        'admin.company-info.update' => 'company-info',
        'admin.company-info.storage' => 'company-info',
        'admin.settings' => 'settings',
        'admin.financing-config' => 'financing-config',

        // Complaints (accessible to admin and editor roles)
        'admin.customer-complaints' => 'customer-complaints',
        'admin.complaints' => 'complaints',

        // Storage (accessible to all authenticated users for image picker)
        'admin.storage' => null,

        // Monitoring (accessible to admin roles)
        'admin.audit-trails' => 'audit-trails',
        'admin.visitor-stats' => 'visitor-stats',
        'admin.security-monitor' => 'security-monitor',
        'admin.database-backup' => 'database-backup',

        // Super Admin Only
        'admin.menu-permissions' => 'menu-permissions',
        'admin.roles' => 'roles',
        'admin.users' => 'users',
    ];

    /**
     * Menu key to permission name mapping
     */
    protected array $menuPermissionMap = [
        'hero-slides' => 'settings.hero',
        'news' => 'news.view',
        'products' => 'products.view',
        'auctions' => 'auctions.view',
        'reports' => 'reports.view',
        'company-info' => 'settings.company',
        'board-members' => 'board.manage',
        'offices' => 'offices.view',
        'careers' => 'careers.view',
        'brochures' => 'brochures.view',
        'why-choose-us' => 'content.manage',
        'kas-keliling' => 'kas-keliling.view',
        'customer-complaints' => 'complaints.view',
        'complaints' => 'complaints.view',
        'settings' => 'settings.maintenance',
        'financing-config' => 'settings.financing',
        'audit-trails' => 'audit.view',
        'visitor-stats' => 'visitors.view',
        'security-monitor' => 'security.view',
        'database-backup' => 'backup.manage',
        'menu-permissions' => 'settings.menu',
        'roles' => 'roles.view',
        'users' => 'users.view',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $routeName = $request->route()->getName();

        // Log access attempt for debugging
        Log::info('Admin access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->getRoleName(),
            'route' => $routeName,
            'method' => $request->method(),
            'ip' => $request->ip(),
        ]);

        // Super admin has access to everything
        if ($user->isSuperAdmin() || $user->id === 1) {
            Log::info('Access granted: Super Admin');
            return $next($request);
        }

        // Check if route is always accessible
        if ($this->isAlwaysAccessible($routeName)) {
            Log::info('Access granted: Always accessible route');
            return $next($request);
        }

        // Check if route requires super admin access
        if ($this->requiresSuperAdmin($routeName)) {
            Log::warning('Super admin access required', [
                'user_id' => $user->id,
                'route' => $routeName,
            ]);
            abort(403, 'Akses ini hanya untuk Super Admin.');
        }

        // Check menu-based permissions
        $menuKey = $this->findMenuKey($routeName);

        Log::info('Menu permission check', [
            'route' => $routeName,
            'menu_key' => $menuKey,
            'user_role' => $user->getRoleName(),
        ]);

        if ($menuKey && !$this->canAccessMenu($user, $menuKey)) {
            Log::warning('Menu access denied', [
                'user_id' => $user->id,
                'user_role' => $user->getRoleName(),
                'route' => $routeName,
                'menu_key' => $menuKey,
            ]);
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }

        Log::info('Access granted: Permission check passed');
        return $next($request);
    }

    /**
     * Check if route is always accessible to authenticated users
     */
    protected function isAlwaysAccessible(string $routeName): bool
    {
        // Check exact matches
        if (in_array($routeName, $this->alwaysAccessibleRoutes)) {
            return true;
        }

        // Check patterns
        $alwaysAccessiblePatterns = [
            'admin.profile',
            'admin.storage.api',
            'admin.storage.upload-editor-image',
            'session.',
        ];

        foreach ($alwaysAccessiblePatterns as $pattern) {
            if (str_starts_with($routeName, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if route requires super admin access
     */
    protected function requiresSuperAdmin(string $routeName): bool
    {
        foreach ($this->superAdminOnlyRoutes as $route) {
            if (str_starts_with($routeName, $route)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user can access menu
     */
    protected function canAccessMenu($user, string $menuKey): bool
    {
        // Admin users have access to most content
        if ($user->isAdmin()) {
            return true;
        }

        // Check specific permission
        $permissionName = $this->menuPermissionMap[$menuKey] ?? null;
        if ($permissionName && $user->hasPermission($permissionName)) {
            return true;
        }

        // Check via AdminMenuPermission system
        if ($user->role_id) {
            $menu = AdminMenu::where('key', $menuKey)->first();
            if ($menu && $menu->canAccessByRoleId($user->role_id)) {
                return true;
            }
        }

        // Fallback: Allow editors to access content management
        if ($user->isEditor()) {
            $editorAccessibleMenus = [
                'news',
                'products',
                'auctions',
                'reports',
                'board-members',
                'offices',
                'careers',
                'brochures',
                'why-choose-us',
                'kas-keliling',
                'customer-complaints',
                'complaints'
            ];

            if (in_array($menuKey, $editorAccessibleMenus)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find menu key from route name
     */
    protected function findMenuKey(string $routeName): ?string
    {
        // Direct match
        if (array_key_exists($routeName, $this->routeMenuMap)) {
            return $this->routeMenuMap[$routeName];
        }

        // Partial match (for routes like admin.news.index, admin.news.create, etc.)
        foreach ($this->routeMenuMap as $routePrefix => $menuKey) {
            if (str_starts_with($routeName, $routePrefix)) {
                return $menuKey;
            }
        }

        // If no match found, allow access (route not restricted)
        return null;
    }
}
