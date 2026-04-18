<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ComplaintSettingMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Tambah permission
        Permission::updateOrCreate(
            ['name' => 'settings.complaints'],
            ['display_name' => 'Pengaturan Pengaduan Nasabah', 'group' => 'settings']
        );

        // Tambah menu
        $menu = AdminMenu::updateOrCreate(
            ['key' => 'complaint-settings'],
            [
                'name'      => 'Pengaturan Pengaduan',
                'route'     => 'admin.settings.complaint',
                'section'   => 'Layanan',
                'order'     => 32,
                'is_active' => true,
            ]
        );

        // Berikan akses ke super_admin dan admin
        $roles = Role::whereIn('name', ['super_admin', 'admin'])->get();
        foreach ($roles as $role) {
            AdminMenuPermission::updateOrCreate(
                ['admin_menu_id' => $menu->id, 'role_id' => $role->id],
                ['can_access' => true]
            );
        }

        // Assign permission ke super_admin dan admin roles
        $permission = Permission::where('name', 'settings.complaints')->first();
        if ($permission) {
            foreach ($roles as $role) {
                if (!$role->permissions()->where('permission_id', $permission->id)->exists()) {
                    $role->permissions()->attach($permission->id);
                }
            }
        }

        AdminMenu::clearCache();

        $this->command->info('Menu & permission complaint-settings berhasil ditambahkan.');
    }
}
