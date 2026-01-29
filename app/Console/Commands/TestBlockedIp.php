<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlockedIp;
use Illuminate\Support\Facades\Cache;

class TestBlockedIp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:blocked-ip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test BlockedIp Caching Logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testIp = '192.168.1.99';
        $this->info("Testing BlockedIp Caching Logic for IP: $testIp");
        $this->info("Current Cache Driver: " . config('cache.default'));

        // 1. Clean up first
        BlockedIp::unblockIp($testIp);
        Cache::forget('blocked_ip:' . $testIp);
        $this->info("[OK] Cleanup done.");

        // 2. Check isBlocked (should be false and not cached yet)
        $isBlocked = BlockedIp::isBlocked($testIp);
        $this->line("isBlocked initial: " . ($isBlocked ? 'YES' : 'NO'));
        if ($isBlocked) {
            $this->error("[FAIL] IP should not be blocked yet.");
            return 1;
        }

        // 3. Block IP
        $this->info("Blocking IP...");
        BlockedIp::blockIp($testIp, 'Test blocking', 1); // 1 hour

        // 4. Check cache directly
        $cachedValue = Cache::get('blocked_ip:' . $testIp);
        $this->line("Cache value check: " . ($cachedValue ? 'PRESENT' : 'MISSING'));

        if (!$cachedValue) {
            $this->error("[FAIL] Cache should be present after blocking.");
            return 1;
        }

        // 5. Check isBlocked again (should be true via cache)
        $isBlocked = BlockedIp::isBlocked($testIp);
        $this->line("isBlocked after block: " . ($isBlocked ? 'YES' : 'NO'));

        if (!$isBlocked) {
            $this->error("[FAIL] IP should be blocked.");
            return 1;
        }

        // 6. Unblock IP
        $this->info("Unblocking IP...");
        BlockedIp::unblockIp($testIp);

        // 7. Check cache again (should be gone)
        $cachedValue = Cache::get('blocked_ip:' . $testIp);
        $this->line("Cache value check after unblock: " . ($cachedValue ? 'PRESENT' : 'GONE'));

        if ($cachedValue) {
            $this->error("[FAIL] Cache should be gone after unblock.");
            return 1;
        }

        $this->info("[SUCCESS] All checks passed!");
        return 0;
    }
}
