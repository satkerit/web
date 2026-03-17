<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password 
                            {email : Email user yang akan direset passwordnya}
                            {--password= : Password baru (opsional, akan digenerate otomatis jika tidak diisi)}
                            {--show : Tampilkan password baru di console}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password untuk user tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $customPassword = $this->option('password');
        $showPassword = $this->option('show');

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email '{$email}' tidak ditemukan.");
            return 1;
        }

        // Generate password jika tidak disediakan
        if ($customPassword) {
            $newPassword = $customPassword;
        } else {
            $newPassword = Str::random(12); // Password 12 karakter acak
        }

        // Update password user
        $user->password = Hash::make($newPassword);
        $user->save();

        // Tampilkan informasi
        $this->info("✅ Password berhasil direset untuk user:");
        $this->line("   Nama: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Role: {$user->getRoleDisplayName()}");

        if ($showPassword) {
            $this->info("   Password Baru: {$newPassword}");
        } else {
            $this->warn("   Password Baru: {$newPassword}");
            $this->line("   (Gunakan opsi --show untuk menampilkan password di console)");
        }

        $this->newLine();
        $this->info("📝 Informasi Login:");
        $this->line("   URL Login: " . url('/admin/login'));
        $this->line("   Email: {$user->email}");
        $this->line("   Password: {$newPassword}");

        // Simpan ke log untuk keamanan
        \Log::info("Password reset untuk user: {$user->email}", [
            'user_id' => $user->id,
            'name' => $user->name,
            'reset_by' => 'console_command',
            'timestamp' => now()->toDateTimeString(),
        ]);

        return 0;
    }
}