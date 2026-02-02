<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ClearDdosBlocks extends Command
{
    protected $signature = 'clear:ddos-blocks {ip=127.0.0.1}';
    protected $description = 'Clear DDoS protection blocks and rate limits for an IP';

    public function handle()
    {
        $ip = $this->argument('ip');

        $this->info("Clearing DDoS Protection for IP: {$ip}");

        // Clear temporary block
        $blockKey = "admin_ddos_block:{$ip}";
        if (Cache::has($blockKey)) {
            Cache::forget($blockKey);
            $this->info("✓ Cleared temporary block");
        }

        // Clear violations
        $violationKey = "admin_violations:{$ip}";
        if (Cache::has($violationKey)) {
            Cache::forget($violationKey);
            $this->info("✓ Cleared violations");
        }

        // Clear failed requests
        $failedKey = "admin_failed:{$ip}";
        if (Cache::has($failedKey)) {
            Cache::forget($failedKey);
            $this->info("✓ Cleared failed requests");
        }

        // Clear rate limiters
        $keys = [
            "admin_burst:{$ip}",
            "admin_minute:{$ip}:19",
            "admin_minute:{$ip}:guest",
            "admin_hour:{$ip}:19",
            "admin_hour:{$ip}:guest",
        ];

        foreach ($keys as $key) {
            RateLimiter::clear($key);
        }
        $this->info("✓ Cleared rate limiters");

        $this->newLine();
        $this->info("All DDoS protection blocks and limits cleared for {$ip}");

        return 0;
    }
}
