<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DebugCompanyProfileUpload extends Command
{
    protected $signature = 'debug:company-profile-upload {user_id?}';
    protected $description = 'Debug specific user permissions for company profile upload';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $this->debugSpecificUser($userId);
        } else {
            $this->showAllUsers();
        }
        
        return 0;
    }
    
    protected function showAllUsers()
    {
        $this->info('=== ALL USERS PERMISSION STATUS ===');
        $users = User::with('roleModel')->get();
        
        $this->table(
            ['ID', 'Name', 'Role', 'Role ID', 'Has settings.company', 'Can Access'],
            $users->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->role,
                    $user->role_id ?? 'NULL',
                    $user->hasAnyPermission(['settings.company']) ? '✓' : '✗',
                    $this->canAccessCompanyInfo($user) ? '✓' : '✗'
                ];
            })
        );
        
        $this->newLine();
        $this->info('To debug specific user: php artisan debug:company-profile-upload {user_id}');
    }
    
    protected function debugSpecificUser($userId)
    {
        $user = User::with('roleModel')->find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return;
        }
        
        $this->info("=== DEBUGGING USER: {$user->name} (ID: {$userId}) ===");
        $this->newLine();
        
        // Basic info
        $this->line("Basic Info:");
        $this->line("  - Name: {$user->name}");
        $this->line("  - Email: {$user->email}");
        $this->line("  - Role (string): {$user->role}");
        $this->line("  - Role ID: " . ($user->role_id ?? 'NULL'));
        $this->line("  - Created: {$user->created_at}");
        $this->newLine();
        
        // Role model info
        if ($user->role_id && $user->roleModel) {
            $this->line("Role Model Info:");
            $this->line("  - Role Name: {$user->roleModel->name}");
            $this->line("  - Display Name: {$user->roleModel->display_name}");
            $this->line("  - Is Active: " . ($user->roleModel->is_active ? 'YES' : 'NO'));
            $this->line("  - Is System: " . ($user->roleModel->is_system ? 'YES' : 'NO'));
            
            $permissions = $user->roleModel->permissions()->pluck('name')->toArray();
            $this->line("  - Permissions Count: " . count($permissions));
            if (in_array('settings.company', $permissions)) {
                $this->line("  - Has settings.company: ✓ YES");
            } else {
                $this->line("  - Has settings.company: ✗ NO");
            }
        } else {
            $this->line("Role Model: NULL (using legacy system)");
        }
        $this->newLine();
        
        // Permission checks
        $this->line("Permission Checks:");
        $this->line("  - isSuperAdmin(): " . ($user->isSuperAdmin() ? '✓' : '✗'));
        $this->line("  - isAdmin(): " . ($user->isAdmin() ? '✓' : '✗'));
        $this->line("  - isEditor(): " . ($user->isEditor() ? '✓' : '✗'));
        $this->line("  - hasAnyPermission(['settings.company']): " . ($user->hasAnyPermission(['settings.company']) ? '✓' : '✗'));
        $this->line("  - hasPermission('settings.company'): " . ($user->hasPermission('settings.company') ? '✓' : '✗'));
        $this->newLine();
        
        // Access checks
        $this->line("Access Checks:");
        $canAccessCompanyInfo = $this->canAccessCompanyInfo($user);
        $canAccessStorageApi = $this->canAccessStorageApi($user);
        
        $this->line("  - Can access company-info: " . ($canAccessCompanyInfo ? '✓' : '✗'));
        $this->line("  - Can access storage API: " . ($canAccessStorageApi ? '✓' : '✗'));
        $this->newLine();
        
        if (!$canAccessCompanyInfo) {
            $this->error("❌ USER CANNOT ACCESS COMPANY INFO!");
            $this->line("Solutions:");
            $this->line("1. Assign 'admin' or 'super_admin' role");
            $this->line("2. Give 'settings.company' permission to user's role");
            $this->line("3. Run: php artisan fix:user-permissions {$userId}");
        } else {
            $this->info("✅ User can access company info");
        }
        
        if (!$canAccessStorageApi) {
            $this->error("❌ USER CANNOT ACCESS STORAGE API (image picker won't work)!");
        } else {
            $this->info("✅ User can access storage API");
        }
    }
    
    protected function canAccessCompanyInfo($user): bool
    {
        return $user->isSuperAdmin() || $user->hasAnyPermission(['settings.company']);
    }
    
    protected function canAccessStorageApi($user): bool
    {
        return $user->isAdmin() ||
               $user->isEditor() ||
               $user->hasPermission('storage.view') ||
               $user->hasAnyPermission([
                   'settings.company',
                   'news.create', 'news.edit',
                   'products.create', 'products.edit',
                   'auctions.create', 'auctions.edit',
                   'board.manage'
               ]);
    }
}