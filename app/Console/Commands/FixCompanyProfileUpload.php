<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class FixCompanyProfileUpload extends Command
{
    protected $signature = 'fix:company-profile-upload';
    protected $description = 'Diagnose and fix 403 errors on company profile image upload';

    public function handle()
    {
        $this->info('=== DIAGNOSIS & PERBAIKAN UPLOAD GAMBAR PROFIL PERUSAHAAN ===');
        $this->newLine();

        // 1. Cek current user dan permissions
        $this->info('1. CHECKING USER PERMISSIONS...');
        try {
            $users = User::with('roleModel')->get();
            
            foreach ($users as $user) {
                $this->line("User: {$user->name} (ID: {$user->id})");
                $this->line("  - Role: " . ($user->roleModel?->display_name ?? 'N/A'));
                $this->line("  - Role ID: " . ($user->role_id ?? 'NULL'));
                $this->line("  - Has settings.company: " . ($user->hasAnyPermission(['settings.company']) ? 'YES' : 'NO'));
                $this->line("  - Is Admin: " . ($user->isAdmin() ? 'YES' : 'NO'));
                $this->line("  - Is Super Admin: " . ($user->isSuperAdmin() ? 'YES' : 'NO'));
                $this->newLine();
            }
        } catch (\Exception $e) {
            $this->error("Error checking users: " . $e->getMessage());
        }

        // 2. Cek permissions dan roles
        $this->info('2. CHECKING ROLES AND PERMISSIONS...');
        try {
            $permission = Permission::where('name', 'settings.company')->first();
            if ($permission) {
                $this->line("Permission 'settings.company' exists (ID: {$permission->id})");
                
                $roles = Role::whereHas('permissions', function($q) use ($permission) {
                    $q->where('permission_id', $permission->id);
                })->get();
                
                $this->line("Roles with settings.company permission:");
                foreach ($roles as $role) {
                    $this->line("  - {$role->name} ({$role->display_name})");
                }
            } else {
                $this->error("ERROR: Permission 'settings.company' NOT FOUND!");
            }
        } catch (\Exception $e) {
            $this->error("Error checking permissions: " . $e->getMessage());
        }

        // 3. Cek storage directories
        $this->newLine();
        $this->info('3. CHECKING STORAGE DIRECTORIES...');
        $directories = [
            'storage/app/public',
            'storage/app/public/company',
            'storage/app/public/company/profile',
            'public/storage'
        ];

        foreach ($directories as $dir) {
            if (file_exists($dir)) {
                $perms = substr(sprintf('%o', fileperms($dir)), -4);
                $this->line("✓ {$dir} exists (permissions: {$perms})");
            } else {
                $this->error("✗ {$dir} MISSING!");
            }
        }

        // 4. Cek symlink
        $this->newLine();
        $this->info('4. CHECKING STORAGE SYMLINK...');
        if (is_link('public/storage')) {
            $target = readlink('public/storage');
            $this->line("✓ Symlink exists: public/storage -> {$target}");
        } else {
            $this->error("✗ Storage symlink MISSING!");
            $this->line("Running: php artisan storage:link");
            try {
                Artisan::call('storage:link');
                $this->line("✓ Storage link created successfully");
            } catch (\Exception $e) {
                $this->error("✗ Failed to create storage link: " . $e->getMessage());
            }
        }

        // 5. Test storage write
        $this->newLine();
        $this->info('5. TESTING STORAGE WRITE...');
        try {
            $testFile = 'company/profile/test-write.txt';
            Storage::disk('public')->put($testFile, 'Test write access');
            
            if (Storage::disk('public')->exists($testFile)) {
                $this->line("✓ Storage write test successful");
                Storage::disk('public')->delete($testFile);
                $this->line("✓ Storage delete test successful");
            } else {
                $this->error("✗ Storage write test failed");
            }
        } catch (\Exception $e) {
            $this->error("✗ Storage test error: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('=== FIXES ===');

        // Fix 1: Ensure directories exist
        $this->line("Creating missing directories...");
        $dirsToCreate = [
            'storage/app/public/company',
            'storage/app/public/company/profile'
        ];

        foreach ($dirsToCreate as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
                $this->line("✓ Created: {$dir}");
            }
        }

        // Fix 2: Run role permission seeder
        $this->line("Running role permission seeder...");
        try {
            Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
            $this->line("✓ Role permissions updated");
        } catch (\Exception $e) {
            $this->error("✗ Seeder failed: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('=== RECOMMENDATIONS ===');
        $this->line("1. Pastikan user memiliki role 'admin', 'editor', atau 'super_admin'");
        $this->line("2. Atau berikan permission 'settings.company' ke role user");
        $this->line("3. Cek log Laravel di storage/logs/laravel.log untuk error detail");
        $this->line("4. Test upload dengan user yang memiliki role super_admin");

        $this->newLine();
        $this->info('Done!');
        
        return 0;
    }
}