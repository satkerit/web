<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DebugUserPermissions extends Command
{
    protected $signature = 'debug:user-permissions {user_id?}';
    protected $description = 'Debug user permissions for company profile upload';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if (!$userId) {
            $this->info('Available users:');
            $users = User::select('id', 'name', 'email', 'role_id')->get();
            foreach ($users as $user) {
                $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role ID: {$user->role_id}");
            }
            
            $userId = $this->ask('Enter user ID to debug');
        }
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $this->info("=== User Permission Debug ===");
        $this->line("User ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Role ID: {$user->role_id}");
        
        // Check if user has role
        if ($user->role_id) {
            $role = $user->role;
            $this->line("Role Name: " . ($role ? $role->name : 'Role not found'));
        } else {
            $this->error("User has no role_id assigned!");
        }
        
        $this->info("\n=== Method Checks ===");
        
        // Check methods
        try {
            $isAdmin = $user->isAdmin();
            $this->line("isAdmin(): " . ($isAdmin ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->error("isAdmin() error: " . $e->getMessage());
        }
        
        try {
            $isEditor = $user->isEditor();
            $this->line("isEditor(): " . ($isEditor ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->error("isEditor() error: " . $e->getMessage());
        }
        
        $this->info("\n=== Permission Checks ===");
        
        $permissions = [
            'storage.view',
            'settings.company',
            'news.create',
            'news.edit',
            'products.create',
            'products.edit',
            'auctions.create',
            'auctions.edit',
            'board.manage',
            'hero.manage',
            'offices.manage',
            'careers.manage',
            'brochures.manage',
            'why-choose-us.manage'
        ];
        
        foreach ($permissions as $permission) {
            try {
                $hasPermission = $user->hasPermission($permission);
                $this->line("hasPermission('{$permission}'): " . ($hasPermission ? 'YES' : 'NO'));
            } catch (\Exception $e) {
                $this->error("hasPermission('{$permission}') error: " . $e->getMessage());
            }
        }
        
        $this->info("\n=== Storage API Access Check ===");
        
        try {
            $allowed = $user->isAdmin() ||
                       $user->isEditor() ||
                       $user->hasPermission('storage.view') ||
                       $user->hasAnyPermission([
                           'settings.company',
                           'news.create', 'news.edit',
                           'products.create', 'products.edit',
                           'auctions.create', 'auctions.edit',
                           'board.manage',
                           'hero.manage',
                           'offices.manage',
                           'careers.manage',
                           'brochures.manage',
                           'why-choose-us.manage'
                       ]);
            
            if ($allowed) {
                $this->info("✅ User SHOULD have access to storage API");
            } else {
                $this->error("❌ User does NOT have access to storage API");
            }
        } catch (\Exception $e) {
            $this->error("Storage API check error: " . $e->getMessage());
        }
        
        $this->info("\n=== Company Info Access Check ===");
        
        try {
            $hasCompanyAccess = $user->hasAnyPermission(['settings.company']);
            if ($hasCompanyAccess) {
                $this->info("✅ User SHOULD have access to company info");
            } else {
                $this->error("❌ User does NOT have access to company info");
            }
        } catch (\Exception $e) {
            $this->error("Company info check error: " . $e->getMessage());
        }
        
        return 0;
    }
}