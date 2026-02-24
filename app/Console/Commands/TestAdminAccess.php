<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:test-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test akses admin untuk user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        $this->info("Testing Admin Access untuk: {$user->name}");
        $this->line('');

        // Cek role
        $this->line("Role: {$user->role}");
        $this->line("Status: " . ($user->is_active ? 'Aktif' : 'Tidak Aktif'));
        
        // Cek apakah bisa login ke admin
        $canAccessAdmin = $user->is_active && in_array($user->role, ['super_admin', 'admin']);
        $this->line("Bisa Login Admin: " . ($canAccessAdmin ? '✅ Ya' : '❌ Tidak'));

        if ($canAccessAdmin) {
            $this->line('');
            $this->info("✅ User ini memiliki akses penuh ke panel admin!");
            $this->line("URL Login: " . url('/admin/login'));
            $this->warn("⚠️  Pastikan untuk mengganti password setelah login pertama kali.");
        }

        return 0;
    }
}