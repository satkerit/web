<?php

namespace App\Console\Commands;

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FixKasKelilingMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:fix-kas-keliling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix kas keliling menu visibility issue by clearing cache and verifying permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('Fix Kas Keliling Menu');
        $this->info('===========================================');
        $this->newLine();

        // Step 1: Check if menu exists
        $this->info('Step 1: Checking if kas-keliling menu exists...');
        $menu = AdminMenu::where('key', 'kas-keliling')->first();

        if (!$menu) {
            $this->error('Menu NOT FOUND! Creating menu...');
            
            $menu = AdminMenu::create([
                'key' => 'kas-keliling',
                'name' => 'Kas Keliling',
                'route' => 'admin.kas-keliling.index',
                'section' => 'Perusahaan',
                'order' => 23,
                'is_active' => true
            ]);
            
            $this->info('✅ Menu created successfully!');
        } else {
            $this->info('✅ Menu exists: ' . $menu->name);
            $this->line('   - ID: ' . $menu->id);
            $this->line('   - Route: ' . $menu->route);
            $this->line('   - Section: ' . $menu->section);
            $this->line('   - Is Active: ' . ($menu->is_active ? 'YES' : 'NO'));
        }

        // Step 2: Check permissions
        $this->newLine();
        $this->info('Step 2: Checking permissions...');
        $permissions = AdminMenuPermission::where('admin_menu_id', $menu->id)->get();

        if ($permissions->isEmpty()) {
            $this->error('No permissions found! Creating permissions...');
            
            $roles = ['super_admin', 'admin', 'editor'];
            foreach ($roles as $role) {
                AdminMenuPermission::create([
                    'admin_menu_id' => $menu->id,
                    'role' => $role,
                    'can_access' => true
                ]);
                $this->line('   ✅ Created permission for: ' . $role);
            }
        } else {
            $this->info('✅ Permissions found:');
            foreach ($permissions as $perm) {
                $roleName = $perm->role?->name ?? 'Unknown';
                $this->line('   - ' . $roleName . ': ' . ($perm->can_access ? 'CAN ACCESS' : 'NO ACCESS'));
            }
        }

        // Step 3: Clear all caches
        $this->newLine();
        $this->info('Step 3: Clearing caches...');

        // Clear AdminMenu cache
        AdminMenu::clearCache();
        $this->line('   ✅ AdminMenu cache cleared');

        // Clear application cache
        Cache::flush();
        $this->line('   ✅ Application cache cleared');

        // Clear specific cache keys
        $roles = ['super_admin', 'admin', 'editor', 'content_manager', 'viewer'];
        foreach ($roles as $role) {
            Cache::forget("admin_menus_{$role}");
            Cache::forget("admin_menus_grouped_{$role}");
        }
        $this->line('   ✅ Cleared role-specific caches');

        // Clear database cache table
        try {
            DB::table('cache')->where('key', 'like', '%admin_menu%')->delete();
            $this->line('   ✅ Cleared database cache table');
        } catch (\Exception $e) {
            $this->warn('   ⚠️  Could not clear database cache: ' . $e->getMessage());
        }

        // Step 4: Verify menu is accessible
        $this->newLine();
        $this->info('Step 4: Verifying menu accessibility...');

        $allAccessible = true;
        foreach (['super_admin', 'admin', 'editor'] as $role) {
            $menus = AdminMenu::getMenusForRole($role);
            $kasKelilingMenu = $menus->firstWhere('key', 'kas-keliling');
            
            if ($kasKelilingMenu) {
                $this->line('   ✅ Menu accessible for ' . $role);
            } else {
                $this->error('   ❌ Menu NOT accessible for ' . $role);
                $allAccessible = false;
            }
        }

        // Step 5: Show grouped menus
        $this->newLine();
        $this->info('Step 5: Checking grouped menus...');
        $groupedMenus = AdminMenu::getGroupedMenusForRole('admin');

        if (isset($groupedMenus['Perusahaan'])) {
            $kasKelilingInGroup = collect($groupedMenus['Perusahaan'])->firstWhere('key', 'kas-keliling');
            if ($kasKelilingInGroup) {
                $this->line('   ✅ Kas Keliling found in \'Perusahaan\' section');
            } else {
                $this->error('   ❌ Kas Keliling NOT found in \'Perusahaan\' section');
                $allAccessible = false;
            }
        } else {
            $this->error('   ❌ \'Perusahaan\' section not found');
            $allAccessible = false;
        }

        // Final summary
        $this->newLine();
        $this->info('===========================================');
        $this->info('Summary');
        $this->info('===========================================');
        $this->line('Menu ID: ' . $menu->id);
        $this->line('Menu Key: ' . $menu->key);
        $this->line('Menu Name: ' . $menu->name);
        $this->line('Is Active: ' . ($menu->is_active ? 'YES' : 'NO'));
        $this->line('Permissions: ' . $permissions->count());
        $this->line('Cache: CLEARED');
        $this->newLine();

        if ($allAccessible) {
            $this->info('✅ Fix completed successfully!');
            $this->newLine();
            $this->comment('Next steps:');
            $this->line('1. Refresh your browser (Ctrl+F5 or Cmd+Shift+R)');
            $this->line('2. Clear browser cache if needed');
            $this->line('3. Logout and login again');
            $this->line('4. Check if menu appears in admin panel');
            return Command::SUCCESS;
        } else {
            $this->error('⚠️  Some issues were found. Please check the output above.');
            $this->newLine();
            $this->comment('Try running:');
            $this->line('php artisan cache:clear');
            $this->line('php artisan config:clear');
            $this->line('php artisan view:clear');
            return Command::FAILURE;
        }
    }
}
