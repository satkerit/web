<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-superadmin
                            {--name= : Nama lengkap user}
                            {--email= : Email user}
                            {--password= : Password user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat user baru dengan role Super Admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('========================================');
        $this->info('   CREATE SUPER ADMIN USER');
        $this->info('========================================');
        $this->newLine();

        // Get input
        $name = $this->option('name') ?? $this->ask('Nama Lengkap');
        $email = $this->option('email') ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');

        // Validate
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validasi gagal:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return self::FAILURE;
        }

        // Confirm
        $this->newLine();
        $this->info('Data yang akan dibuat:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama', $name],
                ['Email', $email],
                ['Password', str_repeat('*', strlen($password))],
                ['Role', 'Super Admin'],
                ['Status', 'Active'],
            ]
        );

        if (!$this->confirm('Lanjutkan membuat user?', true)) {
            $this->warn('Dibatalkan.');
            return self::FAILURE;
        }

        // Create user
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => User::ROLE_SUPER_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->newLine();
            $this->info('✅ User Super Admin berhasil dibuat!');
            $this->newLine();
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $user->id],
                    ['Nama', $user->name],
                    ['Email', $user->email],
                    ['Role', $user->role],
                    ['Status', $user->is_active ? 'Active' : 'Inactive'],
                    ['Created At', $user->created_at->format('d M Y H:i:s')],
                ]
            );

            $this->newLine();
            $this->info('Login URL: ' . url('/admin/login'));
            $this->info('Email: ' . $user->email);
            $this->warn('Password: (yang Anda masukkan tadi)');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
