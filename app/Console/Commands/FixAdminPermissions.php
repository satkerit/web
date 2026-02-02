<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AdminMenu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FixAdminPermissions extends Command
{
    protected $signature = 'fix:admin-permissions';
    protected $description = 'Fix admin permissions and roles to resolve 403 errors';

    public function handle()
    {
        $this->info('🔧 Fixing Admin Permissions and Roles...');
        
        // 1. Ensure basic roles exist
        $this->info('📋 Creating/updating basic roles...');
        $this->ensureBasicRoles();
        
        // 2. Ensure basic permissions exist
        $this->info('🔐 Creating/updating permissions...');
        $this->ensureBasicPermissions();
        
        // 3. Assign permissions to roles
        $this->info('🔗 Assigning permissions to roles...');
        $this->assignPermissionsToRoles();
        
        // 4. Ensure admin menus exist
        $this->info('📝 Creating/updating admin menus...');
        $this->ensureAdminMenus();
        
        // 5. Fix user roles
        $this->info('👤 Fixing user roles...');
        $this->fixUserRoles();
        
        // 6. Clear caches
        $this->info('🧹 Clearing caches...');
        $this->clearCaches();
        
        $this->info('✅ Admin permissions fix completed!');
        
        return 0;
    }
    
    private function ensureBasicRoles()
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to most features',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Content management access',
                'is_system' => true,
                'is_active' => true,
            ],
        ];
        
        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
            $this->line("  ✓ Role: {$role->display_name}");
        }
    }
    
    private function ensureBasicPermissions()
    {
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'group' => 'Dashboard'],
            
            // Content Management
            ['name' => 'news.view', 'display_name' => 'Manage News', 'group' => 'Content'],
            ['name' => 'products.view', 'display_name' => 'Manage Products', 'group' => 'Content'],
            ['name' => 'auctions.view', 'display_name' => 'Manage Auctions', 'group' => 'Content'],
            ['name' => 'reports.view', 'display_name' => 'Manage Reports', 'group' => 'Content'],
            ['name' => 'board.manage', 'display_name' => 'Manage Board Members', 'group' => 'Content'],
            ['name' => 'offices.view', 'display_name' => 'Manage Offices', 'group' => 'Content'],
            ['name' => 'careers.view', 'display_name' => 'Manage Careers', 'group' => 'Content'],
            ['name' => 'brochures.view', 'display_name' => 'Manage Brochures', 'group' => 'Content'],
            ['name' => 'content.manage', 'display_name' => 'General Content Management', 'group' => 'Content'],
            ['name' => 'kas-keliling.view', 'display_name' => 'Manage Kas Keliling', 'group' => 'Content'],
            
            // Settings
            ['name' => 'settings.company', 'display_name' => 'Company Settings', 'group' => 'Settings'],
            ['name' => 'settings.hero', 'display_name' => 'Hero Slider Settings', 'group' => 'Settings'],
            ['name' => 'settings.maintenance', 'display_name' => 'Maintenance Settings', 'group' => 'Settings'],
            ['name' => 'settings.financing', 'display_name' => 'Financing Settings', 'group' => 'Settings'],
            ['name' => 'settings.email', 'display_name' => 'Email Settings', 'group' => 'Settings'],
            ['name' => 'settings.menu', 'display_name' => 'Menu Permissions', 'group' => 'Settings'],
            
            // Complaints
            ['name' => 'complaints.view', 'display_name' => 'Manage Complaints', 'group' => 'Support'],
            
            // Storage
            ['name' => 'storage.view', 'display_name' => 'File Manager', 'group' => 'System'],
            
            // Monitoring
            ['name' => 'audit.view', 'display_name' => 'Audit Trails', 'group' => 'Monitoring'],
            ['name' => 'visitors.view', 'display_name' => 'Visitor Statistics', 'group' => 'Monitoring'],
            ['name' => 'security.view', 'display_name' => 'Security Monitoring', 'group' => 'Monitoring'],
            
            // System Management
            ['name' => 'backup.manage', 'display_name' => 'Database Backup', 'group' => 'System'],
            ['name' => 'users.view', 'display_name' => 'User Management', 'group' => 'System'],
            ['name' => 'roles.view', 'display_name' => 'Role Management', 'group' => 'System'],
        ];
        
        foreach ($permissions as $permData) {
            $permission = Permission::updateOrCreate(
                ['name' => $permData['name']],
                $permData
            );
            $this->line("  ✓ Permission: {$permission->display_name}");
        }
    }
    
    private function assignPermissionsToRoles()
    {
        // Super Admin gets all permissions
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->syncPermissions($allPermissions->pluck('id')->toArray());
            $this->line("  ✓ Super Admin: All permissions assigned");
        }
        
        // Admin gets most permissions except user/role management
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereNotIn('name', [
                'users.view', 'roles.view', 'settings.menu'
            ])->get();
            $admin->syncPermissions($adminPermissions->pluck('id')->toArray());
            $this->line("  ✓ Admin: Content and settings permissions assigned");
        }
        
        // Editor gets content management permissions
        $editor = Role::where('name', 'editor')->first();
        if ($editor) {
            $editorPermissions = Permission::whereIn('name', [
                'dashboard.view',
                'news.view',
                'products.view',
                'auctions.view',
                'reports.view',
                'board.manage',
                'offices.view',
                'careers.view',
                'brochures.view',
                'content.manage',
                'kas-keliling.view',
                'complaints.view',
                'storage.view',
            ])->get();
            $editor->syncPermissions($editorPermissions->pluck('id')->toArray());
            $this->line("  ✓ Editor: Content management permissions assigned");
        }
    }
    
    private function ensureAdminMenus()
    {
        $menus = [
            ['key' => 'dashboard', 'name' => 'Dashboard', 'icon' => 'dashboard', 'order' => 1],
            ['key' => 'hero-slides', 'name' => 'Hero Slider', 'icon' => 'image', 'order' => 2],
            ['key' => 'news', 'name' => 'Berita', 'icon' => 'newspaper', 'order' => 3],
            ['key' => 'products', 'name' => 'Produk', 'icon' => 'package', 'order' => 4],
            ['key' => 'auctions', 'name' => 'Lelang', 'icon' => 'gavel', 'order' => 5],
            ['key' => 'reports', 'name' => 'Laporan', 'icon' => 'file-text', 'order' => 6],
            ['key' => 'board-members', 'name' => 'Dewan', 'icon' => 'users', 'order' => 7],
            ['key' => 'offices', 'name' => 'Kantor', 'icon' => 'building', 'order' => 8],
            ['key' => 'careers', 'name' => 'Karir', 'icon' => 'briefcase', 'order' => 9],
            ['key' => 'brochures', 'name' => 'Brosur', 'icon' => 'book', 'order' => 10],
            ['key' => 'kas-keliling', 'name' => 'Kas Keliling', 'icon' => 'truck', 'order' => 11],
            ['key' => 'customer-complaints', 'name' => 'Pengaduan Nasabah', 'icon' => 'message-circle', 'order' => 12],
            ['key' => 'complaints', 'name' => 'Whistleblowing', 'icon' => 'alert-triangle', 'order' => 13],
            ['key' => 'company-info', 'name' => 'Info Perusahaan', 'icon' => 'info', 'order' => 14],
            ['key' => 'settings', 'name' => 'Pengaturan', 'icon' => 'settings', 'order' => 15],
            ['key' => 'financing-config', 'name' => 'Konfigurasi Pembiayaan', 'icon' => 'calculator', 'order' => 16],
            ['key' => 'storage', 'name' => 'File Manager', 'icon' => 'folder', 'order' => 17],
            ['key' => 'audit-trails', 'name' => 'Log Aktivitas', 'icon' => 'activity', 'order' => 18],
            ['key' => 'visitor-stats', 'name' => 'Statistik Pengunjung', 'icon' => 'bar-chart', 'order' => 19],
            ['key' => 'security-monitor', 'name' => 'Monitor Keamanan', 'icon' => 'shield', 'order' => 20],
            ['key' => 'database-backup', 'name' => 'Backup Database', 'icon' => 'database', 'order' => 21],
            ['key' => 'users', 'name' => 'Pengguna', 'icon' => 'user', 'order' => 22],
            ['key' => 'roles', 'name' => 'Role', 'icon' => 'key', 'order' => 23],
            ['key' => 'menu-permissions', 'name' => 'Permission Menu', 'icon' => 'lock', 'order' => 24],
        ];
        
        foreach ($menus as $menuData) {
            $menu = AdminMenu::updateOrCreate(
                ['key' => $menuData['key']],
                $menuData
            );
            $this->line("  ✓ Menu: {$menu->name}");
        }
    }
    
    private function fixUserRoles()
    {
        // Ensure user ID 1 is super admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $user1 = User::find(1);
            if ($user1) {
                $user1->update(['role_id' => $superAdminRole->id]);
                $this->line("  ✓ User ID 1 set as Super Admin");
            }
        }
        
        // Set default role for users without role
        $defaultRole = Role::where('name', 'editor')->first();
        if ($defaultRole) {
            $usersWithoutRole = User::whereNull('role_id')->get();
            foreach ($usersWithoutRole as $user) {
                $user->update(['role_id' => $defaultRole->id]);
                $this->line("  ✓ User {$user->name} assigned Editor role");
            }
        }
    }
    
    private function clearCaches()
    {
        // Clear Laravel caches
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        
        // Clear role permission caches
        Role::clearAllCache();
        
        $this->line("  ✓ All caches cleared");
    }
}