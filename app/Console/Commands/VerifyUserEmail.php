<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi email user';

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

        if ($user->email_verified_at) {
            $this->warn("Email user {$email} sudah terverifikasi sebelumnya.");
            return 0;
        }

        $user->email_verified_at = now();
        $user->save();

        $this->info("✅ Email user {$email} berhasil diverifikasi!");
        return 0;
    }
}