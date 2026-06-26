<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Dashboard
            ['key' => 'dashboard', 'name' => 'Dashboard', 'route' => 'admin.dashboard', 'section' => null, 'order' => 1],

            // Konten
            ['key' => 'hero-slides', 'name' => 'Slides', 'route' => 'admin.hero-slides.index', 'section' => 'Konten', 'order' => 10],
            ['key' => 'news', 'name' => 'Berita', 'route' => 'admin.news.index', 'section' => 'Konten', 'order' => 11],
            ['key' => 'products', 'name' => 'Produk', 'route' => 'admin.products.index', 'section' => 'Konten', 'order' => 12],
            ['key' => 'brochures', 'name' => 'Brosur Pembiayaan', 'route' => 'admin.brochures.index', 'section' => 'Konten', 'order' => 13],
            ['key' => 'auctions', 'name' => 'Lelang Agunan', 'route' => 'admin.auctions.index', 'section' => 'Konten', 'order' => 14],
            ['key' => 'reports', 'name' => 'Laporan', 'route' => 'admin.reports.index', 'section' => 'Konten', 'order' => 15],
            ['key' => 'why-choose-us', 'name' => 'Keunggulan', 'route' => 'admin.why-choose-us.index', 'section' => 'Konten', 'order' => 16],

            // Perusahaan
            ['key' => 'company-info', 'name' => 'Profil Perusahaan', 'route' => 'admin.company-info.edit', 'section' => 'Perusahaan', 'order' => 20],
            ['key' => 'board-members', 'name' => 'Manajemen', 'route' => 'admin.board-members.index', 'section' => 'Perusahaan', 'order' => 21],
            ['key' => 'offices', 'name' => 'Kantor', 'route' => 'admin.offices.index', 'section' => 'Perusahaan', 'order' => 22],
            ['key' => 'kas-keliling', 'name' => 'Kas Keliling', 'route' => 'admin.kas-keliling.index', 'section' => 'Perusahaan', 'order' => 23],
            ['key' => 'careers', 'name' => 'Karir', 'route' => 'admin.careers.index', 'section' => 'Perusahaan', 'order' => 24],

            // Layanan
            ['key' => 'customer-complaints', 'name' => 'Pengaduan Nasabah', 'route' => 'admin.customer-complaints.index', 'section' => 'Layanan', 'order' => 30],
            ['key' => 'complaints', 'name' => 'Whistleblowing', 'route' => 'admin.complaints.index', 'section' => 'Layanan', 'order' => 31],
            ['key' => 'complaint-settings', 'name' => 'Pengaturan Pengaduan', 'route' => 'admin.settings.complaint', 'section' => 'Layanan', 'order' => 32],

            // Sistem
            ['key' => 'storage', 'name' => 'File Manager', 'route' => 'admin.storage.index', 'section' => 'Sistem', 'order' => 40],
            ['key' => 'database-backup', 'name' => 'Backup Database', 'route' => 'admin.database-backup.index', 'section' => 'Sistem', 'order' => 41],
            ['key' => 'site-settings', 'name' => 'Pengaturan Website', 'route' => 'admin.site-settings.index', 'section' => 'Sistem', 'order' => 42],
            ['key' => 'composer-update', 'name' => 'Composer Update', 'route' => 'admin.composer-update.index', 'section' => 'Sistem', 'order' => 43],
            ['key' => 'settings', 'name' => 'Pengaturan Maintenance', 'route' => 'admin.settings.maintenance', 'section' => 'Sistem', 'order' => 44],
            ['key' => 'security-settings', 'name' => 'Keamanan', 'route' => 'admin.settings.security', 'section' => 'Sistem', 'order' => 45],
            ['key' => 'email-settings', 'name' => 'Email / SMTP', 'route' => 'admin.settings.email', 'section' => 'Sistem', 'order' => 46],
            ['key' => 'financing-config', 'name' => 'Simulasi Pembiayaan', 'route' => 'admin.financing-config.index', 'section' => 'Sistem', 'order' => 47],
            ['key' => 'audit-trails', 'name' => 'Log Aktivitas', 'route' => 'admin.audit-trails.index', 'section' => 'Sistem', 'order' => 48],
            ['key' => 'visitor-stats', 'name' => 'Statistik Pengunjung', 'route' => 'admin.visitor-stats.index', 'section' => 'Sistem', 'order' => 49],
            ['key' => 'menu-permissions', 'name' => 'Hak Akses Menu', 'route' => 'admin.menu-permissions.index', 'section' => 'Sistem', 'order' => 50],
            ['key' => 'roles', 'name' => 'Manajemen Role', 'route' => 'admin.roles.index', 'section' => 'Sistem', 'order' => 51],
            ['key' => 'users', 'name' => 'Pengguna', 'route' => 'admin.users.index', 'section' => 'Sistem', 'order' => 52],
        ];

        // Default permissions per role
        $defaultPermissions = [
            'super_admin' => ['dashboard', 'hero-slides', 'news', 'products', 'brochures', 'auctions', 'reports', 'why-choose-us', 'company-info', 'board-members', 'offices', 'kas-keliling', 'careers', 'customer-complaints', 'complaints', 'complaint-settings', 'storage', 'database-backup', 'site-settings', 'composer-update', 'settings', 'security-settings', 'email-settings', 'financing-config', 'audit-trails', 'visitor-stats', 'menu-permissions', 'roles', 'users'],
            'admin' => ['dashboard', 'hero-slides', 'news', 'products', 'brochures', 'auctions', 'reports', 'why-choose-us', 'company-info', 'board-members', 'offices', 'kas-keliling', 'careers', 'customer-complaints', 'complaints', 'complaint-settings', 'storage', 'database-backup', 'site-settings', 'settings', 'security-settings', 'email-settings', 'financing-config', 'audit-trails', 'visitor-stats'],
            'editor' => ['dashboard', 'hero-slides', 'news', 'products', 'brochures', 'auctions', 'reports', 'why-choose-us', 'company-info', 'board-members', 'offices', 'kas-keliling', 'careers'],
        ];

        // Get roles
        $roles = Role::all()->keyBy('name');

        foreach ($menus as $menuData) {
            $menu = AdminMenu::updateOrCreate(
                ['key' => $menuData['key']],
                $menuData
            );

            // Create permissions for each role using role_id
            foreach ($defaultPermissions as $roleName => $allowedMenus) {
                $role = $roles->get($roleName);
                if ($role) {
                    AdminMenuPermission::updateOrCreate(
                        ['admin_menu_id' => $menu->id, 'role_id' => $role->id],
                        ['can_access' => in_array($menuData['key'], $allowedMenus)]
                    );
                }
            }
        }

        AdminMenu::clearCache();
    }
}
