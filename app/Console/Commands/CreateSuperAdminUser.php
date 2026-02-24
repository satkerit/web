<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-super-admin {email} {password} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super admin user with full access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        // Cek apakah email sudah digunakan
        if (User::where('email', $email)->exists()) {
            $this->error('User dengan email ' . $email . ' sudah ada!');
            return 1;
        }

        // Cari role super admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            // Buat role super admin jika belum ada
            $superAdminRole = Role::create([
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'User dengan akses penuh ke seluruh sistem'
            ]);
            $this->info('Role super_admin berhasil dibuat');
        }

        // Buat user baru
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_active' => true
        ]);

        // Assign role super admin
        $user->roles()->attach($superAdminRole->id);

        $this->info('User super admin berhasil dibuat!');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
        $this->info('Name: ' . $name);

        return 0;
    }
}