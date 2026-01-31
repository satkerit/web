<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', User::ROLE_SUPER_ADMIN)->first();
        $adminRole = Role::where('name', User::ROLE_ADMIN)->first();
        $editorRole = Role::where('name', User::ROLE_EDITOR)->first();

        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@bprsyariah.co.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@bprsyariah.co.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Editor
        User::updateOrCreate(
            ['email' => 'editor@bprsyariah.co.id'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'),
                'role_id' => $editorRole?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
