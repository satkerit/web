<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TroubleshootPermissions extends Command
{
    protected $signature = 'troubleshoot:permissions {permission_name}';
    protected $description = 'Troubleshoot permission issues untuk debugging';

    public function handle(): int
    {
        $permissionName = $this->argument('permission_name');

        $this->info("=== Troubleshooting Permission: {$permissionName} ===");
        $this->newLine();

        // 1. Cek apakah permission ada
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            $this->error("✗ Permission '{$permissionName}' TIDAK DITEMUKAN di database!");
            $this->info("Membuat permission baru...");
            
            $permission = Permission::create([
                'name' => $permissionName,
                'display_name' => ucwords(str_replace(['.', '_'], ' ', $permissionName)),
                'group' => explode('.', $permissionName)[0] ?? 'general',
            ]);
            
            $this->info("✓ Permission berhasil dibuat dengan ID: {$permission->id}");
        } else {
            $this->info("✓ Permission ditemukan:");
            $this->info("  - ID: {$permission->id}");
            $this->info("  - Name: {$permission->name}");
            $this->info("  - Display Name: {$permission->display_name}");
            $this->info("  - Group: {$permission->group}");
        }

        $this->newLine();

        // 2. Cek role mana saja yang memiliki permission ini
        $this->info("=== Roles dengan Permission ini ===");
        $roles = Role::whereHas('permissions', function ($query) use ($permission) {
            $query->where('permission_id', $permission->id);
        })->get();

        if ($roles->isEmpty()) {
            $this->warn("Tidak ada role yang memiliki permission ini!");
        } else {
            foreach ($roles as $role) {
                $this->info("✓ {$role->display_name} ({$role->name})");
            }
        }

        $this->newLine();

        // 3. Cek user yang seharusnya memiliki akses
        $this->info("=== User dengan Akses ===");
        
        $adminRoles = Role::whereIn('name', ['super_admin', 'admin'])->pluck('id');
        $users = User::with('roleModel')->whereIn('role_id', $adminRoles)->get();

        foreach ($users as $user) {
            $hasPermission = $user->hasPermission($permissionName);
            $icon = $hasPermission ? '✓' : '✗';
            $status = $hasPermission ? 'DAPAT' : 'TIDAK DAPAT';
            
            $this->info("{$icon} {$user->name} ({$user->getRoleName()}) - {$status} akses");
        }

        $this->newLine();

        // 4. Cek cache
        $this->info("=== Cache Status ===");
        foreach ($roles as $role) {
            $cacheKey = "role_{$role->id}_permission_{$permissionName}";
            $cached = Cache::has($cacheKey);
            
            if ($cached) {
                $value = Cache::get($cacheKey) ? 'TRUE' : 'FALSE';
                $this->info("Cache untuk {$role->name}: {$value}");
            } else {
                $this->info("Cache untuk {$role->name}: TIDAK ADA");
            }
        }

        $this->newLine();

        // 5. Opsi perbaikan
        if ($this->confirm('Apakah Anda ingin assign permission ini ke role Admin?', true)) {
            $adminRole = Role::where('name', 'admin')->first();
            
            if ($adminRole) {
                if (!$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                    $adminRole->permissions()->attach($permission->id);
                    $this->info("✓ Permission berhasil di-assign ke role Admin");
                } else {
                    $this->info("Permission sudah ter-assign ke role Admin");
                }
            }
        }

        if ($this->confirm('Apakah Anda ingin clear cache permission?', true)) {
            Role::clearAllCache();
            Cache::flush();
            $this->info("✓ Cache berhasil dibersihkan");
        }

        $this->newLine();
        $this->info("=== Troubleshooting Selesai ===");

        return 0;
    }
}
