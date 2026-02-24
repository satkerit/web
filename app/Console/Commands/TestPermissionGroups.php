<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;

class TestPermissionGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:test-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test permission groups method';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Testing Permission::getGroups() method...");
            
            $groups = Permission::getGroups();
            
            $this->info("✅ Method getGroups() berhasil dipanggil!");
            $this->line("Jumlah grup: " . count($groups));
            
            if (!empty($groups)) {
                $this->line("Daftar grup permission:");
                foreach ($groups as $group) {
                    $this->line("  - {$group}");
                }
            } else {
                $this->warn("Tidak ada grup permission yang ditemukan.");
            }

            // Test getGroupedPermissions juga
            $this->line('');
            $this->info("Testing Permission::getGroupedPermissions() method...");
            
            $groupedPermissions = Permission::getGroupedPermissions();
            
            $this->info("✅ Method getGroupedPermissions() berhasil dipanggil!");
            $this->line("Jumlah grup dengan permissions: " . $groupedPermissions->count());
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }
}