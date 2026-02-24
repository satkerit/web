<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list {--search=} {--limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan daftar user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $search = $this->option('search');
        $limit = $this->option('limit');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->limit($limit)->get();

        if ($users->isEmpty()) {
            $this->info('Tidak ada user yang ditemukan.');
            return 0;
        }

        $this->info("Daftar User ({$users->count()} user):");
        $this->line('');

        $headers = ['ID', 'Nama', 'Email', 'Dibuat'];
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at->format('d/m/Y H:i'),
            ];
        }

        $this->table($headers, $data);

        if ($search) {
            $this->line('');
            $this->info("Hasil pencarian untuk: {$search}");
        }

        return 0;
    }
}