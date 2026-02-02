<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class VerifyUserPermissions extends Command
{
    protected $signature = 'verify:user-permissions {user_id?}';
    protected $description = 'Verifikasi permission user untuk Company Info';

    public function handle(): int
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $users = User::with('roleModel')->where('id', $userId)->get();
            if ($users->isEmpty()) {
                $this->error("User dengan ID {$userId} tidak ditemukan!");
                return 1;
            }
        } else {
            // Ambil semua user admin dan super admin
            $adminRoleIds = Role::whereIn('name', ['admin', 'super_admin'])->pluck('id');
            $users = User::with('roleModel')->whereIn('role_id', $adminRoleIds)->get();
        }

        $this->info('Memverifikasi permission user...');
        $this->newLine();

        foreach ($users as $user) {
            $this->info("User: {$user->name} (ID: {$user->id})");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->getRoleDisplayName()} ({$user->getRoleName()})");
            
            // Cek berbagai method
            $isSuperAdmin = $user->isSuperAdmin();
            $isAdmin = $user->isAdmin();
            $hasPermission = $user->hasPermission('settings.company');
            
            $this->info("Is Super Admin: " . ($isSuperAdmin ? 'Ya' : 'Tidak'));
            $this->info("Is Admin: " . ($isAdmin ? 'Ya' : 'Tidak'));
            $this->info("Has Permission 'settings.company': " . ($hasPermission ? 'Ya' : 'Tidak'));
            
            if ($hasPermission) {
                $this->info("✓ User ini DAPAT mengakses Company Info");
            } else {
                $this->error("✗ User ini TIDAK DAPAT mengakses Company Info");
            }
            
            $this->newLine();
        }

        // Clear all permission cache
        $this->info('Membersihkan cache permission...');
        Role::clearAllCache();
        Cache::flush();
        
        $this->info('✓ Cache berhasil dibersihkan.');

        return 0;
    }
}
