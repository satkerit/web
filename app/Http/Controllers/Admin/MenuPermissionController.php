<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\User;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeAny(['settings.menu']);

        $menus = AdminMenu::getAllWithPermissions();
        $roles = User::getRoles();

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

        // Get all menus
        $menus = AdminMenu::all();

        foreach ($menus as $menu) {
            foreach (User::getRoles() as $role => $roleName) {
                $canAccess = isset($permissions[$menu->id][$role]);

                AdminMenuPermission::updateOrCreate(
                    ['admin_menu_id' => $menu->id, 'role' => $role],
                    ['can_access' => $canAccess]
                );
            }
        }

        // Clear cache
        AdminMenu::clearCache();

        return redirect()->route('admin.menu-permissions.index')
            ->with('success', 'Hak akses menu berhasil diperbarui.');
    }
}
