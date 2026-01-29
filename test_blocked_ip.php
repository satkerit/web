<?php

use App\Models\BlockedIp;
use Illuminate\Support\Facades\Cache;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$testIp = '192.168.1.99';

echo "Testing BlockedIp Caching Logic...\n";

// 1. Clean up first
BlockedIp::unblockIp($testIp);
Cache::forget('blocked_ip:' . $testIp);
echo "[OK] Cleanup done.\n";

// 2. Check isBlocked (should be false and not cached yet)
$isBlocked = BlockedIp::isBlocked($testIp);
echo "isBlocked initial: " . ($isBlocked ? 'YES' : 'NO') . "\n";
if ($isBlocked) die("[FAIL] IP should not be blocked yet.\n");

// 3. Block IP
echo "Blocking IP...\n";
BlockedIp::blockIp($testIp, 'Test blocking', 1); // 1 hour

// 4. Check cache directly
$cachedValue = Cache::get('blocked_ip:' . $testIp);
echo "Cache value check: " . ($cachedValue ? 'PRESENT' : 'MISSING') . "\n";

if (!$cachedValue) die("[FAIL] Cache should be present after blocking.\n");

// 5. Check isBlocked again (should be true via cache)
$isBlocked = BlockedIp::isBlocked($testIp);
echo "isBlocked after block: " . ($isBlocked ? 'YES' : 'NO') . "\n";

if (!$isBlocked) die("[FAIL] IP should be blocked.\n");

// 6. Unblock IP
echo "Unblocking IP...\n";
BlockedIp::unblockIp($testIp);

// 7. Check cache again (should be gone)
$cachedValue = Cache::get('blocked_ip:' . $testIp);
echo "Cache value check after unblock: " . ($cachedValue ? 'PRESENT' : 'GONE') . "\n";

if ($cachedValue) die("[FAIL] Cache should be gone after unblock.\n");

echo "[SUCCESS] All checks passed!\n";
