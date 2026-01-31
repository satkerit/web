<?php

namespace App\Console\Commands;

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixMenuPermissions extends Command
{
    protected $signature = 'fix:menu-permissions';
    protected $description = 'Fix menu permissions after role migration';

    public function handle()
    {
        $this->info('Fixing menu permissions...');
        
        try {
            DB::beginTransaction();
            
            // 1. Check if we have any permissions with old 'role' field
            $oldPermissions = DB::table('admin_menu_permissions')
                ->whereNotNull('role')
                ->whereNull('role_id')
                ->get();
                
            if ($oldPermissions->count() > 0) {
                $this->info("Found {$oldPermissions->count()} old permissions to migrate");
                
                foreach ($oldPermissions as $oldPermission) {
                    $role = Role::where('name', $oldPermission->role)->first();
                    if ($role) {
                        // Update with role_id
                        DB::table('admin_menu_permissions')
                            ->where('id', $oldPermission->id)
                            ->update([
                                'role_id' => $role->id,
                                'role' => null // Clear old field
                            ]);
                        $this->line("  ✅ Migrated permission for role: {$oldPermission->role}");
                    } else {
                        $this->error("  ❌ Role not found: {$oldPermission->role}");
                    }
                }
            }
            
            // 2. Ensure all menus have permissions for all roles
            $menus = AdminMenu::all();
            $roles = Role::all();
            
            $this->info("Ensuring all menus have permissions for all roles...");
            
            foreach ($menus as $menu) {
                foreach ($roles as $role) {
                    $exists = AdminMenuPermission::where('admin_menu_id', $menu->id)
                        ->where('role_id', $role->id)
                        ->exists();
                        
                    if (!$exists) {
                        // Default permissions based on role
                        $defaultAccess = $this->getDefaultAccess($menu->key, $role->name);
                        
                        AdminMenuPermission::create([
                            'admin_menu_id' => $menu->id,
                            'role_id' => $role->id,
                            'can_access' => $defaultAccess
                        ]);
                        
                        $this->line("  ✅ Created permission: {$menu->name} -> {$role->name} ({$defaultAccess ? 'YES' : 'NO'})");
                    }
                }
            }
            
            // 3. Remove any orphaned permissions
            $orphaned = AdminMenuPermission::whereNull('role_id')->count();
            if ($orphaned > 0) {
                AdminMenuPermission::whereNull('role_id')->delete();
                $this->info("Removed {$orphaned} orphaned permissions");
            }
            
            DB::commit();
            
            $this->info('✅ Menu permissions fixed successfully!');
            
            // 4. Show summary
            $this->showSummary();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error fixing menu permissions: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    protected function getDefaultAccess(string $menuKey, string $roleName): bool
    {
        // Default permissions based on role hierarchy
        $permissions = [
            'super_admin' => [
                'dashboard', 'hero-slides', 'why-choose-us', 'news', 'products', 'brochures', 
                'auctions', 'reports', 'company-info', 'board-members', 'offices', 'kas-keliling', 
                'careers', 'customer-complaints', 'complaints', 'storage', 'database-backup', 
                'settings', 'security-settings', 'email-settings', 'financing-config', 
                'audit-trails', 'visitor-stats', 'menu-permissions', 'roles', 'users'
            ],
            'admin' => [
                'dashboard', 'hero-slides', 'why-choose-us', 'news', 'products', 'brochures', 
                'auctions', 'reports', 'company-info', 'board-members', 'offices', 'kas-keliling', 
                'careers', 'customer-complaints', 'complaints', 'storage', 'database-backup', 
                'settings', 'security-settings', 'email-settings', 'financing-config', 
                'audit-trails', 'visitor-stats'
            ],
            'editor' => [
                'dashboard', 'hero-slides', 'why-choose-us', 'news', 'products', 'brochures', 
                'auctions', 'reports', 'company-info', 'board-members', 'offices', 'kas-keliling', 
                'careers'
            ]
        ];
        
        return in_array($menuKey, $permissions[$roleName] ?? []);
    }
    
    protected function showSummary()
    {
        $this->info("\n=== Menu Permissions Summary ===");
        
        $menus = AdminMenu::with(['permissions.role'])->get();
        $roles = Role::orderBy('name')->get();
        
        $this->table(
            ['Menu', ...($roles->pluck('name')->toArray())],
            $menus->map(function ($menu) use ($roles) {
                $row = [$menu->name];
                foreach ($roles as $role) {
                    $permission = $menu->permissions->firstWhere('role_id', $role->id);
                    $row[] = $permission && $permission->can_access ? '✅' : '❌';
                }
                return $row;
            })
        );
    }
}