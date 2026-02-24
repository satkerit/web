<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ShowUserDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:show {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan detail user berdasarkan email';

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

        $this->info("Detail User:");
        $this->line('');
        $this->line("ID: {$user->id}");
        $this->line("Nama: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Role: {$user->role}");
        $this->line("Status: " . ($user->is_active ? 'Aktif' : 'Tidak Aktif'));
        $this->line("Email Terverifikasi: " . ($user->email_verified_at ? 'Ya' : 'Tidak'));
        $this->line("Tanggal Dibuat: {$user->created_at->format('d/m/Y H:i')}");

        // Cek apakah user memiliki akses admin
        $hasAdminAccess = in_array($user->role, ['super_admin', 'admin']);
        $this->line("Akses Admin: " . ($hasAdminAccess ? 'Ya' : 'Tidak'));

        return 0;
    }
}