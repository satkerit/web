<?php

namespace App\Console\Commands;

use App\Models\BlockedIp;
use Illuminate\Console\Command;

class CleanExpiredBlockedIps extends Command
{
    protected $signature = 'security:clean-expired-ips';
    protected $description = 'Remove expired blocked IPs from database';

    public function handle(): int
    {
        $count = BlockedIp::expired()->delete();

        $this->info("Removed {$count} expired blocked IPs.");

        return Command::SUCCESS;
    }
}
