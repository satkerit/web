<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Console\Command;

class FixUserPermissions extends Command
{
    protected $signature = 'fix:user-permissions {user_id}';
    protected $description = 'Fix user permissions for company profile upload';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }
        
        $this->info("Fixing permissions for user: {$user->name}");
        
        // Check if user already has proper access
        if ($user->isSuperAdmin() || $user->hasAnyPermission(['settings.company'])) {
            $this->info("✅ User already has proper permissions!");
            return 0;
        }
        
        // Option 1: Assign admin role if user doesn't have role_id
        if (!$user->role_id) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->role_id = $adminRole->id;
                $user->save();
                
                $this->info("✅ Assigned 'admin' role to user");
                return 0;
            }
        }
        
        // Option 2: Ensure user's role has settings.company permission
        if ($user->role_id && $user->roleModel) {
            $permission = Permission::where('name', 'settings.company')->first();
            if ($permission && !$user->roleModel->permissions()->where('permission_id', $permission->id)->exists()) {
                $user->roleModel->permissions()->attach($permission->id);
                $this->info("✅ Added 'settings.company' permission to user's role");
                return 0;
            }
        }
        
        // Option 3: Create custom role with required permission
        $customRole = Role::firstOrCreate([
            'name' => 'company_editor'
        ], [
            'display_name' => 'Company Editor',
            'description' => 'Can edit company information',
            'is_system' => false,
            'is_active' => true
        ]);
        
        $permission = Permission::where('name', 'settings.company')->first();
        if ($permission) {
            $customRole->permissions()->syncWithoutDetaching([$permission->id]);
        }
        
        $user->role_id = $customRole->id;
        $user->save();
        
        $this->info("✅ Created and assigned 'company_editor' role to user");
        return 0;
    }
}