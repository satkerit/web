<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DebugCompanyInfoPermission extends Command
{
    protected $signature = 'debug:company-info-permission {user_id?}';
    protected $description = 'Debug company info permission for a user';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? auth()->id() ?? 18;
        $user = User::with('roleModel.permissions')->find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $this->info("=== User Information ===");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Role: {$user->getRoleName()}");
        $this->line("Role ID: {$user->role_id}");
        
        $this->info("\n=== Role Checks ===");
        $this->line("Is Super Admin: " . ($user->isSuperAdmin() ? 'Yes' : 'No'));
        $this->line("Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No'));
        $this->line("Is Editor: " . ($user->isEditor() ? 'Yes' : 'No'));
        
        $this->info("\n=== Permission Checks ===");
        $this->line("Has 'settings.company' permission: " . ($user->hasPermission('settings.company') ? 'Yes' : 'No'));
        
        if ($user->roleModel) {
            $this->info("\n=== Role Permissions ===");
            $permissions = $user->roleModel->permissions()->pluck('name')->toArray();
            if (empty($permissions)) {
                $this->warn("No permissions assigned to this role");
            } else {
                foreach ($permissions as $permission) {
                    $this->line("- {$permission}");
                }
            }
        } else {
            $this->warn("\nNo role assigned to this user");
        }

        return 0;
    }
}
