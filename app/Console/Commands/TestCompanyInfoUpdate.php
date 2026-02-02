<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestCompanyInfoUpdate extends Command
{
    protected $signature = 'test:company-info-update {user_id=19}';
    protected $description = 'Test company info update route and permissions';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }

        $this->info("Testing Company Info Update for User: {$user->name} (ID: {$user->id})");
        $this->info("Email: {$user->email}");
        $this->info("Role: {$user->getRoleName()}");
        $this->newLine();

        // Check role methods
        $this->info("Role Checks:");
        $this->line("- isSuperAdmin(): " . ($user->isSuperAdmin() ? 'YES' : 'NO'));
        $this->line("- isAdmin(): " . ($user->isAdmin() ? 'YES' : 'NO'));
        $this->line("- isEditor(): " . ($user->isEditor() ? 'YES' : 'NO'));
        $this->newLine();

        // Check permissions
        $this->info("Permission Checks:");
        $this->line("- hasPermission('settings.company'): " . ($user->hasPermission('settings.company') ? 'YES' : 'NO'));
        $this->line("- canManageSettings(): " . ($user->canManageSettings() ? 'YES' : 'NO'));
        $this->newLine();

        // Check route
        $route = Route::getRoutes()->getByName('admin.company-info.update');
        if ($route) {
            $this->info("Route Information:");
            $this->line("- URI: " . $route->uri());
            $this->line("- Method: " . implode('|', $route->methods()));
            $this->line("- Action: " . $route->getActionName());
            
            $middleware = $route->gatherMiddleware();
            $this->line("- Middleware: " . implode(', ', $middleware));
            $this->newLine();

            // Check if admin.ddos is in middleware
            if (in_array('admin.ddos', $middleware)) {
                $this->warn("⚠ admin.ddos middleware is ACTIVE on this route");
            } else {
                $this->info("✓ admin.ddos middleware is NOT active (bypassed)");
            }
        }

        // Check role_id and roleModel
        $this->info("Role Model Information:");
        $this->line("- role_id: " . ($user->role_id ?? 'NULL'));
        if ($user->roleModel) {
            $this->line("- roleModel->name: " . $user->roleModel->name);
            $this->line("- roleModel->id: " . $user->roleModel->id);
        } else {
            $this->warn("- roleModel: NULL (This might be the problem!)");
        }

        return 0;
    }
}
