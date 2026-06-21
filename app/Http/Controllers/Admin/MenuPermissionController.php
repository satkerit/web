<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\Role;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeAny(['settings.menu']);

        $menus = AdminMenu::getAllWithPermissions();
        
        // Get roles from Role model instead of User::getRoles()
        $roles = Role::orderBy('name')->pluck('display_name', 'name')->toArray();

        return view('admin.menu-permissions.index', compact('menus', 'roles'));
    }

    public function update(Request $request)
    {
        $this->authorizeAny(['settings.menu']);

        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'array',
        ]);

        $permissions = $request->input('permissions', []);

        // Get all menus and roles
        $menus = AdminMenu::all();
        $roles = Role::all()->keyBy('name');

        // Build bulk upsert data
        $upsertData = [];

        foreach ($menus as $menu) {
            foreach ($roles as $roleName => $roleModel) {
                $canAccess = isset($permissions[$menu->id][$roleName]);
                $upsertData[] = [
                    'admin_menu_id' => $menu->id,
                    'role_id' => $roleModel->id,
                    'can_access' => $canAccess,
                ];
            }
        }

        // Use bulk upsert to eliminate N+1 queries
        if (!empty($upsertData)) {
            try {
                AdminMenuPermission::upsert(
                    $upsertData,
                    ['admin_menu_id', 'role_id'],
                    ['can_access']
                );
            } catch (\Exception $e) {
                return redirect()->route('admin.menu-permissions.index')
                    ->with('error', 'Gagal memperbarui hak akses menu: ' . $e->getMessage());
            }
        }

        // Clear cache
        AdminMenu::clearCache();

        return redirect()->route('admin.menu-permissions.index')
            ->with('success', 'Hak akses menu berhasil diperbarui.');
    }
}
