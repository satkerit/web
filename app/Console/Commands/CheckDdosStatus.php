<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class CheckDdosStatus extends Command
{
    protected $signature = 'check:ddos-status {ip=127.0.0.1}';
    protected $description = 'Check DDoS protection status for an IP';

    public function handle()
    {
        $ip = $this->argument('ip');

        $this->info("Checking DDoS Protection Status for IP: {$ip}");
        $this->newLine();

        // Check temporary block
        $blockKey = "admin_ddos_block:{$ip}";
        if (Cache::has($blockKey)) {
            $blockedUntil = Cache::get($blockKey);
            $this->error("✗ IP is TEMPORARILY BLOCKED until: {$blockedUntil}");
        } else {
            $this->info("✓ IP is NOT temporarily blocked");
        }

        // Check violations
        $violationKey = "admin_violations:{$ip}";
        $violations = Cache::get($violationKey, 0);
        if ($violations > 0) {
            $this->warn("⚠ Violations count: {$violations}");
        } else {
            $this->info("✓ No violations recorded");
        }

        // Check failed requests
        $failedKey = "admin_failed:{$ip}";
        $failed = Cache::get($failedKey, 0);
        if ($failed > 0) {
            $this->warn("⚠ Failed requests: {$failed}");
        } else {
            $this->info("✓ No failed requests");
        }

        // Check rate limits
        $this->newLine();
        $this->info("Rate Limit Status:");
        
        $burstKey = "admin_burst:{$ip}";
        $burstAttempts = RateLimiter::attempts($burstKey);
        $this->line("- Burst (per second): {$burstAttempts}/5");

        $minuteKey = "admin_minute:{$ip}:19"; // Assuming user ID 19
        $minuteAttempts = RateLimiter::attempts($minuteKey);
        $this->line("- Per minute: {$minuteAttempts}/60");

        $hourKey = "admin_hour:{$ip}:19";
        $hourAttempts = RateLimiter::attempts($hourKey);
        $this->line("- Per hour: {$hourAttempts}/1000");

        $this->newLine();
        $this->info("To clear all blocks and limits, run:");
        $this->line("php artisan clear:ddos-blocks {$ip}");

        return 0;
    }
}
