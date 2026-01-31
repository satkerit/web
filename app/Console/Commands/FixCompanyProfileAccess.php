<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AdminMenu;
use App\Models\AdminMenuPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCompanyProfileAccess extends Command
{
    protected $signature = 'fix:company-profile-access {user_id?}';
    protected $description = 'Fix user access to company profile upload functionality';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if (!$userId) {
            $this->info('Available users:');
            $users = User::select('id', 'name', 'email', 'role_id')->get();
            foreach ($users as $user) {
                $this->line("ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role ID: {$user->role_id}");
            }
            
            $userId = $this->ask('Enter user ID to fix (or "all" for all users)');
        }
        
        if ($userId === 'all') {
            $users = User::all();
            foreach ($users as $user) {
                $this->fixUserAccess($user);
            }
            $this->info("Fixed access for all users");
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found");
                return 1;
            }
            
            $this->fixUserAccess($user);
            $this->info("Fixed access for user: {$user->name}");
        }
        
        return 0;
    }
    
    protected function fixUserAccess(User $user)
    {
        $this->line("Fixing access for: {$user->name} (ID: {$user->id})");
        
        // 1. Ensure user has a role
        if (!$user->role_id) {
            $editorRole = Role::where('name', 'editor')->first();
            if (!$editorRole) {
                $editorRole = Role::where('name', 'admin')->first();
            }
            
            if ($editorRole) {
                $user->role_id = $editorRole->id;
                $user->save();
                $this->line("  ✅ Assigned role: {$editorRole->name}");
            } else {
                $this->error("  ❌ No suitable role found");
                return;
            }
        }
        
        // 2. Ensure required permissions exist
        $requiredPermissions = [
            'settings.company' => 'Manage company information',
            'storage.view' => 'View storage files',
        ];
        
        foreach ($requiredPermissions as $name => $description) {
            $permission = Permission::firstOrCreate([
                'name' => $name
            ], [
                'description' => $description
            ]);
            $this->line("  ✅ Permission ensured: {$name}");
        }
        
        // 3. Ensure company-info menu exists
        $companyMenu = AdminMenu::firstOrCreate([
            'name' => 'Info Perusahaan'
        ], [
            'route' => 'admin.company-info.edit',
            'icon' => 'building',
            'order' => 50,
            'parent_id' => null,
            'is_active' => true
        ]);
        
        // 4. Grant menu permission to user's role
        if ($user->role_id) {
            AdminMenuPermission::firstOrCreate([
                'admin_menu_id' => $companyMenu->id,
                'role_id' => $user->role_id
            ]);
            $this->line("  ✅ Menu permission granted");
        }
        
        // 5. Test access
        try {
            $hasCompanyAccess = $user->hasAnyPermission(['settings.company']);
            $hasStorageAccess = $user->hasPermission('storage.view') || $user->isAdmin() || $user->isEditor();
            
            if ($hasCompanyAccess && $hasStorageAccess) {
                $this->line("  ✅ User should now have full access");
            } else {
                $this->error("  ❌ Access check failed - company: {$hasCompanyAccess}, storage: {$hasStorageAccess}");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error testing access: " . $e->getMessage());
        }
    }
}