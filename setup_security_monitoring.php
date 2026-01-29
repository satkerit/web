<?php
// Script untuk menambahkan Security Monitoring menu dan permissions

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Adding Security Monitoring Permissions and Menu ===\n\n";

// 1. Add Permissions
echo "1. Adding permissions...\n";
DB::table('permissions')->insertOrIgnore([
    [
        'name' => 'security.view',
        'display_name' => 'Lihat Security Logs',
        'group' => 'security',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'name' => 'security.manage',
        'display_name' => 'Kelola Security (Block/Unblock)',
        'group' => 'security',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
echo "   ✓ Permissions added\n\n";

// 2. Get permission IDs
$securityViewPermId = DB::table('permissions')->where('name', 'security.view')->value('id');
$securityManagePermId = DB::table('permissions')->where('name', 'security.manage')->value('id');

// 3. Get role IDs
$superAdminRoleId = DB::table('roles')->where('name', 'super_admin')->value('id');
$adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

// 4. Assign permissions to roles
echo "2. Assigning permissions to roles...\n";
if ($superAdminRoleId && $securityViewPermId) {
    DB::table('role_permissions')->insertOrIgnore([
        [
            'role_id' => $superAdminRoleId,
            'permission_id' => $securityViewPermId,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'role_id' => $superAdminRoleId,
            'permission_id' => $securityManagePermId,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    echo "   ✓ Permissions assigned to super_admin\n";
}

if ($adminRoleId && $securityViewPermId) {
    DB::table('role_permissions')->insertOrIgnore([
        [
            'role_id' => $adminRoleId,
            'permission_id' => $securityViewPermId,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'role_id' => $adminRoleId,
            'permission_id' => $securityManagePermId,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    echo "   ✓ Permissions assigned to admin\n\n";
}

// 5. Add menu
echo "3. Adding menu...\n";
$menuExists = DB::table('admin_menus')->where('key', 'security-monitor')->exists();

if (!$menuExists) {
    $menuId = DB::table('admin_menus')->insertGetId([
        'key' => 'security-monitor',
        'name' => 'Security Monitoring',
        'route' => 'admin.security-monitor.index',
        'icon' => 'security-monitor',
        'section' => 'Keamanan',
        'order' => 90,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "   ✓ Menu added (ID: $menuId)\n\n";
} else {
    $menuId = DB::table('admin_menus')->where('key', 'security-monitor')->value('id');
    echo "   ✓ Menu already exists (ID: $menuId)\n\n";
}

// 6. Assign menu to roles
echo "4. Assigning menu to roles...\n";
DB::table('admin_menu_permissions')->insertOrIgnore([
    [
        'admin_menu_id' => $menuId,
        'role' => 'super_admin',
        'can_access' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'admin_menu_id' => $menuId,
        'role' => 'admin',
        'can_access' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
echo "   ✓ Menu assigned to super_admin and admin\n\n";

// 7. Clear cache
echo "5. Clearing cache...\n";
Artisan::call('optimize:clear');
echo "   ✓ Cache cleared\n\n";

// 8. Verification
echo "=== VERIFICATION ===\n\n";

$permissions = DB::table('permissions')->where('group', 'security')->get();
echo "Permissions:\n";
foreach ($permissions as $perm) {
    echo "  - {$perm->name} ({$perm->display_name})\n";
}

echo "\nRole Assignments:\n";
$assignments = DB::table('role_permissions as rp')
    ->join('permissions as p', 'rp.permission_id', '=', 'p.id')
    ->join('roles as r', 'rp.role_id', '=', 'r.id')
    ->where('p.group', 'security')
    ->select('r.name as role', 'p.name as permission')
    ->get();
foreach ($assignments as $assign) {
    echo "  - {$assign->role} => {$assign->permission}\n";
}

echo "\nMenu:\n";
$menu = DB::table('admin_menus')->where('key', 'security-monitor')->first();
if ($menu) {
    echo "  - {$menu->name} ({$menu->route})\n";
}

echo "\nMenu Permissions:\n";
$menuPerms = DB::table('admin_menu_permissions as amp')
    ->join('admin_menus as am', 'amp.admin_menu_id', '=', 'am.id')
    ->where('am.key', 'security-monitor')
    ->select('am.name', 'amp.role')
    ->get();
foreach ($menuPerms as $mp) {
    echo "  - {$mp->name} => {$mp->role}\n";
}

echo "\n✅ DONE! Security Monitoring has been integrated.\n";
echo "Access: /admin/security-monitor\n";
