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
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bprsyariah.co.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bprsyariah.co.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Editor
        User::create([
            'name' => 'Editor',
            'email' => 'editor@bprsyariah.co.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_EDITOR,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
