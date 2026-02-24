<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;

class TestRolePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:test-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test role page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Testing Role Controller Methods...");
            
            // Test Permission::getGroups()
            $this->line("Testing Permission::getGroups()...");
            $groups = Permission::getGroups();
            $this->info("✅ getGroups() berhasil! Jumlah grup: " . count($groups));
            
            // Test Permission::getGroupedPermissions()
            $this->line("Testing Permission::getGroupedPermissions()...");
            $groupedPermissions = Permission::getGroupedPermissions();
            $this->info("✅ getGroupedPermissions() berhasil! Jumlah grup: " . $groupedPermissions->count());
            
            // Test Role model
            $this->line("Testing Role model...");
            $role = Role::first();
            if ($role) {
                $this->info("✅ Role model berhasil! Role: {$role->name}");
                
                // Test role permissions relationship
                $permissions = $role->permissions;
                $this->info("✅ Role permissions relationship berhasil! Jumlah permissions: " . $permissions->count());
            } else {
                $this->warn("⚠️  Tidak ada role yang ditemukan");
            }
            
            $this->line('');
            $this->info("✅ Semua test berhasil! Error sudah diperbaiki.");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->line("File: " . $e->getFile() . " (Line: " . $e->getLine() . ")");
            return 1;
        }
    }
}