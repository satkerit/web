<?php

namespace App\Http\Middleware;

use App\Models\AdminMenu;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Route to menu key mapping
     * null = always accessible for authenticated users
     */
    protected array $routeMenuMap = [
        'admin.dashboard' => null, // Dashboard always accessible
        'admin.hero-slides' => 'hero-slides',
        'admin.news' => 'news',
        'admin.products' => 'products',
        'admin.auctions' => 'auctions',
        'admin.reports' => 'reports',
        'admin.company-info' => 'company-info',
        'admin.board-members' => 'board-members',
        'admin.offices' => 'offices',
        'admin.careers' => 'careers',
        'admin.customer-complaints' => 'customer-complaints',
        'admin.complaints' => 'complaints',
        'admin.storage' => 'storage',
        'admin.settings' => 'settings',
        'admin.financing-config' => 'financing-config',
        'admin.audit-trails' => 'audit-trails',
        'admin.visitor-stats' => 'visitor-stats',
        'admin.menu-permissions' => 'menu-permissions',
        'admin.roles' => 'roles',
        'admin.users' => 'users',
        'admin.profile' => null, // Profile accessible by all authenticated users
    ];

    /**
     * Menu key to permission name mapping (for Role-based permission check)
     */
    protected array $menuPermissionMap = [
        'dashboard' => 'dashboard.view',
        'hero-slides' => 'settings.hero',
        'news' => 'news.view',
        'products' => 'products.view',
        'auctions' => 'auctions.view',
        'reports' => 'reports.view',
        'company-info' => 'settings.company',
        'board-members' => 'board.manage',
        'offices' => 'offices.view',
        'careers' => 'careers.view',
        'customer-complaints' => 'complaints.view',
        'complaints' => 'complaints.view',
        'storage' => 'storage.view',
        'settings' => 'settings.maintenance',
        'financing-config' => 'settings.financing',
        'audit-trails' => 'audit.view',
        'visitor-stats' => 'visitors.view',
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

        // Super admin bypass all checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get current route name
        $routeName = $request->route()->getName();

        // Find menu key from route
        $menuKey = $this->findMenuKey($routeName);

        // If menu key is null (like profile), allow access
        if ($menuKey === null) {
            return $next($request);
        }

        if ($menuKey) {
            // Check access using both systems
            if (!$this->canAccessMenu($user, $menuKey)) {
                abort(403, 'Anda tidak memiliki akses ke menu ini.');
            }
        }

        return $next($request);
    }

    /**
     * Check if user can access menu using role-based permission system
     */
    protected function canAccessMenu($user, string $menuKey): bool
    {
        // Check via Role model (new system)
        if ($user->role_id && $user->roleModel) {
            $permissionName = $this->menuPermissionMap[$menuKey] ?? null;
            if ($permissionName && $user->roleModel->hasPermission($permissionName)) {
                return true;
            }

            // Also check via AdminMenuPermission using role_id
            $menu = AdminMenu::where('key', $menuKey)->first();
            if ($menu && $menu->canAccessByRoleId($user->role_id)) {
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
        // Check for profile routes first (always accessible)
        if (str_starts_with($routeName, 'admin.profile')) {
            return null;
        }

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
