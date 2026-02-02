<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FixCompanyInfoPermission extends Command
{
    protected $signature = 'fix:company-info-permission';
    protected $description = 'Fix Company Info permission untuk role Admin';

    public function handle(): int
    {
        $this->info('Memperbaiki permission Company Info...');

        // Cari atau buat permission settings.company
        $permission = Permission::firstOrCreate(
            ['name' => 'settings.company'],
            [
                'display_name' => 'Pengaturan Perusahaan',
                'group' => 'settings',
            ]
        );

        $this->info("Permission 'settings.company' sudah ada dengan ID: {$permission->id}");

        // Cari role Admin
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('Role Admin tidak ditemukan!');
            return 1;
        }

        $this->info("Role Admin ditemukan dengan ID: {$adminRole->id}");

        // Cek apakah permission sudah ter-assign
        if ($adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
            $this->info('Permission sudah ter-assign ke role Admin.');
        } else {
            // Assign permission ke role Admin
            $adminRole->permissions()->attach($permission->id);
            $this->info('Permission berhasil di-assign ke role Admin.');
        }

        // Clear cache permission
        $adminRole->clearPermissionCache();
        Cache::forget("role_{$adminRole->id}_permission_{$permission->name}");
        
        $this->info('Cache permission berhasil dibersihkan.');

        // Verifikasi
        $hasPermission = $adminRole->hasPermission('settings.company');
        
        if ($hasPermission) {
            $this->info('✓ Verifikasi berhasil: Role Admin sekarang memiliki permission settings.company');
            return 0;
        } else {
            $this->error('✗ Verifikasi gagal: Role Admin masih belum memiliki permission settings.company');
            return 1;
        }
    }
}
