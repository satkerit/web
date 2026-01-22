<?php

/**
 * Script untuk memperbaiki menu Kas Keliling yang tidak muncul di production
 * 
 * Masalah: Menu kas keliling muncul di dev tapi tidak di prod
 * Penyebab: Cache yang tidak ter-update di production
 * 
 * Cara menjalankan:
 * php fix_kas_keliling_menu.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "Fix Kas Keliling Menu - Production\n";
echo "===========================================\n\n";

// Step 1: Check if menu exists
echo "Step 1: Checking if kas-keliling menu exists...\n";
$menu = AdminMenu::where('key', 'kas-keliling')->first();

if (!$menu) {
    echo "❌ Menu NOT FOUND! Creating menu...\n";
    
    $menu = AdminMenu::create([
        'key' => 'kas-keliling',
        'name' => 'Kas Keliling',
        'route' => 'admin.kas-keliling.index',
        'section' => 'Perusahaan',
        'order' => 23,
        'is_active' => true
    ]);
    
    echo "✅ Menu created successfully!\n";
} else {
    echo "✅ Menu exists: {$menu->name}\n";
    echo "   - ID: {$menu->id}\n";
    echo "   - Route: {$menu->route}\n";
    echo "   - Section: {$menu->section}\n";
    echo "   - Is Active: " . ($menu->is_active ? 'YES' : 'NO') . "\n";
}

// Step 2: Check permissions
echo "\nStep 2: Checking permissions...\n";
$permissions = AdminMenuPermission::where('admin_menu_id', $menu->id)->get();

if ($permissions->isEmpty()) {
    echo "❌ No permissions found! Creating permissions...\n";
    
    $roles = ['super_admin', 'admin', 'editor'];
    foreach ($roles as $role) {
        AdminMenuPermission::create([
            'admin_menu_id' => $menu->id,
            'role' => $role,
            'can_access' => true
        ]);
        echo "   ✅ Created permission for: {$role}\n";
    }
} else {
    echo "✅ Permissions found:\n";
    foreach ($permissions as $perm) {
        echo "   - {$perm->role}: " . ($perm->can_access ? 'CAN ACCESS' : 'NO ACCESS') . "\n";
    }
}

// Step 3: Clear all caches
echo "\nStep 3: Clearing caches...\n";

// Clear AdminMenu cache
AdminMenu::clearCache();
echo "   ✅ AdminMenu cache cleared\n";

// Clear application cache
Cache::flush();
echo "   ✅ Application cache cleared\n";

// Clear specific cache keys
$roles = ['super_admin', 'admin', 'editor', 'content_manager', 'viewer'];
foreach ($roles as $role) {
    Cache::forget("admin_menus_{$role}");
    Cache::forget("admin_menus_grouped_{$role}");
    echo "   ✅ Cleared cache for role: {$role}\n";
}

// Clear database cache table
try {
    DB::table('cache')->where('key', 'like', '%admin_menu%')->delete();
    echo "   ✅ Cleared database cache table\n";
} catch (\Exception $e) {
    echo "   ⚠️  Could not clear database cache: " . $e->getMessage() . "\n";
}

// Step 4: Verify menu is accessible
echo "\nStep 4: Verifying menu accessibility...\n";

foreach (['super_admin', 'admin', 'editor'] as $role) {
    $menus = AdminMenu::getMenusForRole($role);
    $kasKelilingMenu = $menus->firstWhere('key', 'kas-keliling');
    
    if ($kasKelilingMenu) {
        echo "   ✅ Menu accessible for {$role}\n";
    } else {
        echo "   ❌ Menu NOT accessible for {$role}\n";
    }
}

// Step 5: Show grouped menus
echo "\nStep 5: Checking grouped menus...\n";
$groupedMenus = AdminMenu::getGroupedMenusForRole('admin');

if (isset($groupedMenus['Perusahaan'])) {
    $kasKelilingInGroup = collect($groupedMenus['Perusahaan'])->firstWhere('key', 'kas-keliling');
    if ($kasKelilingInGroup) {
        echo "   ✅ Kas Keliling found in 'Perusahaan' section\n";
    } else {
        echo "   ❌ Kas Keliling NOT found in 'Perusahaan' section\n";
    }
} else {
    echo "   ❌ 'Perusahaan' section not found\n";
}

// Step 6: Final summary
echo "\n===========================================\n";
echo "Summary\n";
echo "===========================================\n";
echo "Menu ID: {$menu->id}\n";
echo "Menu Key: {$menu->key}\n";
echo "Menu Name: {$menu->name}\n";
echo "Is Active: " . ($menu->is_active ? 'YES' : 'NO') . "\n";
echo "Permissions: {$permissions->count()}\n";
echo "Cache: CLEARED\n";
echo "\n";
echo "✅ Fix completed!\n";
echo "\nNext steps:\n";
echo "1. Refresh your browser (Ctrl+F5 or Cmd+Shift+R)\n";
echo "2. Clear browser cache if needed\n";
echo "3. Logout and login again\n";
echo "4. Check if menu appears in admin panel\n";
echo "\nIf menu still doesn't appear, run:\n";
echo "php artisan cache:clear\n";
echo "php artisan config:clear\n";
echo "php artisan view:clear\n";
echo "===========================================\n";
