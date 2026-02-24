<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email} {--password=} {--generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password untuk user berdasarkan email';

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

        // Jika ada opsi generate, buat password random
        if ($this->option('generate')) {
            $password = $this->generateRandomPassword();
            $this->info("Password baru yang dihasilkan: {$password}");
        } else {
            // Jika ada opsi password, gunakan itu
            $password = $this->option('password');
            
            if (!$password) {
                // Tanya user untuk input password
                $password = $this->secret('Masukkan password baru:');
                $confirmPassword = $this->secret('Konfirmasi password baru:');
                
                if ($password !== $confirmPassword) {
                    $this->error('Password tidak cocok!');
                    return 1;
                }
            }
        }

        // Update password
        $user->password = Hash::make($password);
        $user->save();

        $this->info("✅ Password untuk user {$user->name} ({$email}) berhasil direset!");
        
        if ($this->option('generate')) {
            $this->warn('⚠️  Simpan password ini dengan aman! Password tidak dapat dilihat lagi.');
        }

        return 0;
    }

    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $password;
    }
}