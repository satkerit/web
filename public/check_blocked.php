<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BlockedIp;

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Handle X-Forwarded-For if behind proxy
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Blocked IP Check</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .status { padding: 10px; border-radius: 5px; display: inline-block; margin: 10px 0; }
        .blocked { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .clean { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .btn { display: inline-block; padding: 10px 20px; background: #2196f3; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Blocked IP Check</h1>
    <p>Your IP: <strong>{$ip}</strong></p>";

try {
    $isBlocked = BlockedIp::where('ip_address', $ip)->exists();
    
    if ($isBlocked) {
        $entry = BlockedIp::where('ip_address', $ip)->first();
        echo "<div class='status blocked'><strong>STATUS: BLOCKED</strong></div>";
        echo "<p>Reason: " . htmlspecialchars($entry->reason) . "</p>";
        echo "<p>Blocked at: " . $entry->created_at . "</p>";
        echo "<p>Expires at: " . ($entry->expires_at ?? 'Never') . "</p>";
        
        // Button to unblock self
        if (isset($_POST['unblock']) && $_POST['unblock'] == '1') {
            $entry->delete();
            echo "<p style='color:green'><strong>SUCCESS: Unblocked self. Please wait 5 seconds and try accessing the site.</strong></p>";
            echo "<script>setTimeout(function(){ window.location.href='/login'; }, 5000);</script>";
        } else {
             echo "<form method='post'>
                <input type='hidden' name='unblock' value='1'>
                <button type='submit' class='btn'>Unblock My IP</button>
             </form>";
        }
    } else {
        echo "<div class='status clean'><strong>STATUS: NOT BLOCKED</strong></div>";
        echo "<p>You should be able to access the site. If you still get 403, it might be a server configuration or another middleware issue.</p>";
        echo "<p><a href='/login' class='btn'>Go to Login</a></p>";
    }
    
    $all = BlockedIp::count();
    echo "<hr><p><small>Total blocked IPs in DB: $all</small></p>";
    
} catch (\Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";
