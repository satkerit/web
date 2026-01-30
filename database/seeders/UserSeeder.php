<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@bprsyariah.co.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPER_ADMIN,
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
                'role' => User::ROLE_ADMIN,
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
                'role' => User::ROLE_EDITOR,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
