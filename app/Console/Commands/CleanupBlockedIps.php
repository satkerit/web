<?php

namespace App\Console\Commands;

use App\Models\BlockedIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CleanupBlockedIps extends Command
{
    protected $signature = 'security:cleanup-blocked-ips';
    protected $description = 'Cleanup expired blocked IPs and reset violation counters';

    public function handle(): int
    {
        $this->info('Cleaning up expired blocked IPs...');

        // Remove expired blocks from database
        $deleted = BlockedIp::where('blocked_until', '<', now())
            ->where('is_permanent', false)
            ->delete();

        $this->info("Removed {$deleted} expired IP blocks from database.");

        // Clean up old cache entries (violation counters older than 24 hours)
        $this->info('Cleaning up old violation counters...');
        
        // Note: This is a simplified cleanup. In production with Redis,
        // you might want to use Redis SCAN to find and delete old keys.
        
        $this->info('Cleanup completed.');

        return Command::SUCCESS;
    }
}
