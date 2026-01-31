<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateOrUpdateUser extends Command
{
    protected $signature = 'user:create-or-update 
                            {email : Email address of the user}
                            {--name= : Name of the user}
                            {--password= : Password for the user}
                            {--role=admin : Role (super_admin, admin, editor)}
                            {--active=1 : Is user active (1 or 0)}';

    protected $description = 'Create a new user or update existing user password';

    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->option('name');
        $password = $this->option('password');
        $role = $this->option('role');
        $isActive = (bool) $this->option('active');

        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address!');
            return Command::FAILURE;
        }

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // Update existing user
            $this->info("User found: {$user->name} ({$user->email})");
            
            if ($password) {
                $user->password = Hash::make($password);
                $this->info('✓ Password updated');
            }

            if ($name) {
                $user->name = $name;
                $this->info('✓ Name updated');
            }

            // Get role model
            $roleModel = Role::where('name', $role)->first();
            if (!$roleModel) {
                $this->error("Role '{$role}' not found!");
                return Command::FAILURE;
            }

            $user->role_id = $roleModel->id;
            $user->is_active = $isActive;
            $user->save();

            $this->newLine();
            $this->info('✅ User updated successfully!');
        } else {
            // Create new user
            if (!$name) {
                $name = $this->ask('Enter user name');
            }

            if (!$password) {
                $password = $this->secret('Enter password');
            }

            // Get role model
            $roleModel = Role::where('name', $role)->first();
            if (!$roleModel) {
                $this->error("Role '{$role}' not found!");
                return Command::FAILURE;
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $roleModel->id,
                'is_active' => $isActive,
            ]);

            $this->newLine();
            $this->info('✅ User created successfully!');
        }

        // Display user info
        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('ID       : ' . $user->id);
        $this->line('Name     : ' . $user->name);
        $this->line('Email    : ' . $user->email);
        $this->line('Role     : ' . ($user->roleModel?->display_name ?? 'N/A'));
        $this->line('Status   : ' . ($user->is_active ? 'Active' : 'Inactive'));
        if ($password) {
            $this->line('Password : ' . $password);
        }
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        return Command::SUCCESS;
    }
}
