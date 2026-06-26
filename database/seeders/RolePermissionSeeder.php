<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'Lihat Dashboard', 'group' => 'dashboard'],

            // Users
            ['name' => 'users.view', 'display_name' => 'Lihat Pengguna', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Tambah Pengguna', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Pengguna', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Hapus Pengguna', 'group' => 'users'],

            // Roles
            ['name' => 'roles.view', 'display_name' => 'Lihat Role', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Tambah Role', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Role', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Hapus Role', 'group' => 'roles'],
            ['name' => 'roles.permissions', 'display_name' => 'Kelola Permission', 'group' => 'roles'],

            // News
            ['name' => 'news.view', 'display_name' => 'Lihat Berita', 'group' => 'news'],
            ['name' => 'news.create', 'display_name' => 'Tambah Berita', 'group' => 'news'],
            ['name' => 'news.edit', 'display_name' => 'Edit Berita', 'group' => 'news'],
            ['name' => 'news.delete', 'display_name' => 'Hapus Berita', 'group' => 'news'],

            // Products
            ['name' => 'products.view', 'display_name' => 'Lihat Produk', 'group' => 'products'],
            ['name' => 'products.create', 'display_name' => 'Tambah Produk', 'group' => 'products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Produk', 'group' => 'products'],
            ['name' => 'products.delete', 'display_name' => 'Hapus Produk', 'group' => 'products'],

            // Auctions
            ['name' => 'auctions.view', 'display_name' => 'Lihat Lelang', 'group' => 'auctions'],
            ['name' => 'auctions.create', 'display_name' => 'Tambah Lelang', 'group' => 'auctions'],
            ['name' => 'auctions.edit', 'display_name' => 'Edit Lelang', 'group' => 'auctions'],
            ['name' => 'auctions.delete', 'display_name' => 'Hapus Lelang', 'group' => 'auctions'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'Lihat Laporan', 'group' => 'reports'],
            ['name' => 'reports.create', 'display_name' => 'Tambah Laporan', 'group' => 'reports'],
            ['name' => 'reports.edit', 'display_name' => 'Edit Laporan', 'group' => 'reports'],
            ['name' => 'reports.delete', 'display_name' => 'Hapus Laporan', 'group' => 'reports'],

            // Offices
            ['name' => 'offices.view', 'display_name' => 'Lihat Kantor', 'group' => 'offices'],
            ['name' => 'offices.create', 'display_name' => 'Tambah Kantor', 'group' => 'offices'],
            ['name' => 'offices.edit', 'display_name' => 'Edit Kantor', 'group' => 'offices'],
            ['name' => 'offices.delete', 'display_name' => 'Hapus Kantor', 'group' => 'offices'],

            // Careers
            ['name' => 'careers.view', 'display_name' => 'Lihat Karir', 'group' => 'careers'],
            ['name' => 'careers.create', 'display_name' => 'Tambah Karir', 'group' => 'careers'],
            ['name' => 'careers.edit', 'display_name' => 'Edit Karir', 'group' => 'careers'],
            ['name' => 'careers.delete', 'display_name' => 'Hapus Karir', 'group' => 'careers'],

            // Complaints
            ['name' => 'complaints.view', 'display_name' => 'Lihat Pengaduan', 'group' => 'complaints'],
            ['name' => 'complaints.manage', 'display_name' => 'Kelola Pengaduan', 'group' => 'complaints'],

            // Settings
            ['name' => 'settings.company', 'display_name' => 'Pengaturan Perusahaan', 'group' => 'settings'],
            ['name' => 'settings.email', 'display_name' => 'Pengaturan Email', 'group' => 'settings'],
            ['name' => 'settings.security', 'display_name' => 'Kelola Keamanan', 'group' => 'settings'],
            ['name' => 'settings.maintenance', 'display_name' => 'Mode Maintenance', 'group' => 'settings'],
            ['name' => 'settings.hero', 'display_name' => 'Kelola Hero Slides', 'group' => 'settings'],
            ['name' => 'settings.financing', 'display_name' => 'Konfigurasi Pembiayaan', 'group' => 'settings'],
            ['name' => 'settings.site', 'display_name' => 'Pengaturan Website', 'group' => 'settings'],
            ['name' => 'settings.menu', 'display_name' => 'Kelola Menu Permission', 'group' => 'settings'],
            ['name' => 'settings.complaints', 'display_name' => 'Pengaturan Pengaduan Nasabah', 'group' => 'settings'],
            ['name' => 'settings.composer', 'display_name' => 'Composer Update', 'group' => 'settings'],

            // Security Monitoring permissions
            ['name' => 'security.view', 'display_name' => 'Lihat Security Logs', 'group' => 'security'],
            ['name' => 'security.manage', 'display_name' => 'Kelola Security (Block/Unblock)', 'group' => 'security'],

            // Audit
            ['name' => 'audit.view', 'display_name' => 'Lihat Log Aktivitas', 'group' => 'audit'],
            ['name' => 'audit.clear', 'display_name' => 'Hapus Log Aktivitas', 'group' => 'audit'],
            ['name' => 'audit.visitors', 'display_name' => 'Statistik Pengunjung', 'group' => 'audit'],

            // Storage
            ['name' => 'storage.view', 'display_name' => 'Lihat File Manager', 'group' => 'settings'],
            ['name' => 'storage.manage', 'display_name' => 'Kelola File', 'group' => 'settings'],

            // Database Backup
            ['name' => 'backup.create', 'display_name' => 'Buat Backup Database', 'group' => 'settings'],
            ['name' => 'backup.restore', 'display_name' => 'Restore Database', 'group' => 'settings'],
            ['name' => 'backup.delete', 'display_name' => 'Hapus Backup', 'group' => 'settings'],

            // Board Members
            ['name' => 'board.view', 'display_name' => 'Lihat Dewan', 'group' => 'content'],
            ['name' => 'board.manage', 'display_name' => 'Kelola Dewan', 'group' => 'content'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create System Roles
        $superAdmin = Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Akses penuh ke semua fitur sistem',
                'is_system' => true,
                'is_active' => true,
            ]
        );

        $admin = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Admin',
                'description' => 'Akses ke sebagian besar fitur kecuali manajemen pengguna dan role',
                'is_system' => true,
                'is_active' => true,
            ]
        );

        $editor = Role::updateOrCreate(
            ['name' => 'editor'],
            [
                'display_name' => 'Editor',
                'description' => 'Akses untuk mengelola konten',
                'is_system' => true,
                'is_active' => true,
            ]
        );

        // Assign all permissions to Super Admin
        $allPermissions = Permission::pluck('id')->toArray();
        $superAdmin->syncPermissions($allPermissions);

        // Assign permissions to Admin (exclude user and role management)
        $adminPermissions = Permission::whereNotIn('group', ['users', 'roles'])
            ->whereNotIn('name', ['audit.clear'])
            ->pluck('id')->toArray();
        $admin->syncPermissions($adminPermissions);

        // Assign permissions to Editor (content only - view, create, edit but not delete)
        $editorPermissions = Permission::where(function ($query) {
            $query->whereIn('group', ['dashboard', 'news', 'products', 'auctions', 'reports', 'content'])
                ->where(function ($q) {
                    $q->where('name', 'like', '%.view')
                        ->orWhere('name', 'like', '%.create')
                        ->orWhere('name', 'like', '%.edit')
                        ->orWhere('name', '=', 'dashboard.view');
                });
        })->pluck('id')->toArray();
        $editor->syncPermissions($editorPermissions);
    }
}
