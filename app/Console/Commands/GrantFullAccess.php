<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use Illuminate\Console\Command;

class GrantFullAccess extends Command
{
    protected $signature = 'user:grant-full-access {email : Email address of the user}';
    protected $description = 'Grant full access (super admin) to a user';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info('🔐 Memberikan Full Hak Akses...');
        $this->newLine();

        // 1. Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ User dengan email {$email} tidak ditemukan!");
            return Command::FAILURE;
        }

        $this->info("✓ User ditemukan: {$user->name}");

        // 2. Set role_id to super_admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if (!$superAdminRole) {
            $this->error('Super Admin role not found. Please run RolePermissionSeeder first.');
            return 1;
        }

        $user->role_id = $superAdminRole->id;
        $user->is_active = true;
        $user->save();
        $this->info("✓ Role ID diset ke: {$superAdminRole->id} ({$superAdminRole->display_name})");

            // Grant all permissions to this role
            $allPermissions = Permission::all();
            if ($allPermissions->count() > 0) {
                $superAdminRole->syncPermissions($allPermissions->pluck('id')->toArray());
                $this->info("✓ Semua permissions ({$allPermissions->count()}) diberikan ke role super_admin");
            }
        }

        // 4. Set all AdminMenuPermission for super_admin
        $allMenus = AdminMenu::all();
        
        if ($allMenus->count() > 0) {
            $this->newLine();
            $this->info('📋 Mengatur Menu Permissions:');

            $bar = $this->output->createProgressBar($allMenus->count());
            $bar->start();

            foreach ($allMenus as $menu) {
                AdminMenuPermission::updateOrCreate(
                    [
                        'admin_menu_id' => $menu->id,
                        'role' => 'super_admin',
                    ],
                    [
                        'can_access' => true,
                    ]
                );
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
        }

        // 5. Summary
        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✅ Full Hak Akses Berhasil Diberikan!');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        $this->table(
            ['Attribute', 'Value'],
            [
                ['User ID', $user->id],
                ['Nama', $user->name],
                ['Email', $user->email],
                ['Role', $user->roleModel?->display_name ?? 'N/A'],
                ['Role ID', $user->role_id ?? 'N/A'],
                ['Status', $user->is_active ? 'Aktif' : 'Tidak Aktif'],
                ['Menu Access', $allMenus->count() . ' menu'],
                ['Permissions', $superAdminRole ? $superAdminRole->permissions()->count() . ' permissions' : 'N/A'],
            ]
        );

        $this->newLine();
        $this->comment('💡 User sekarang memiliki akses penuh ke semua fitur!');

        return Command::SUCCESS;
    }
}
