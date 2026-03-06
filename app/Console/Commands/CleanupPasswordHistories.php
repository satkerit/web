<?php

namespace App\Console\Commands;

use App\Models\PasswordHistory;
use Illuminate\Console\Command;

class CleanupPasswordHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-history:cleanup {--days=365 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old password history records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info("Cleaning up password histories older than {$days} days...");

        $deleted = PasswordHistory::cleanup($days);

        $this->info("Deleted {$deleted} old password history records.");

        return Command::SUCCESS;
    }
}
