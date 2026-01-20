<?php
/**
 * Fix Autoload - BPR SBabel
 * DELETE THIS FILE AFTER USE!
 * Password: bprsbabel2026
 */

$password = 'bprsbabel2026';
if (($_GET['password'] ?? '') !== $password) {
    die('<!DOCTYPE html><html><head><title>Authentication Required</title><style>body{font-family:Arial;max-width:600px;margin:50px auto;padding:20px;background:#f5f5f5;}.box{background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}input{padding:10px;width:250px;font-size:16px;border:1px solid #ddd;border-radius:5px;}button{padding:10px 20px;font-size:16px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;margin-left:10px;}button:hover{background:#0056b3;}</style></head><body><div class="box"><h2>🔒 Authentication Required</h2><p>Enter password to continue:</p><form method="GET"><input type="password" name="password" placeholder="Password" required><button type="submit">Submit</button></form></div></body></html>');
}

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$basePath = dirname(__DIR__);
$results = [];

?><!DOCTYPE html>
<html>
<head>
    <title>Fix Autoload - BPR SBabel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{font-family:Arial,sans-serif;max-width:900px;margin:20px auto;padding:20px;background:#f5f5f5;}
        .container{background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        h1{color:#333;border-bottom:3px solid #007bff;padding-bottom:10px;}
        .step{background:#f8f9fa;border-left:4px solid #007bff;padding:15px;margin:15px 0;border-radius:5px;}
        .success{background:#d4edda;border-left-color:#28a745;color:#155724;}
        .error{background:#f8d7da;border-left-color:#dc3545;color:#721c24;}
        .warning{background:#fff3cd;border-left-color:#ffc107;color:#856404;}
        .info{background:#d1ecf1;border-left-color:#17a2b8;color:#0c5460;}
        pre{background:#2d2d2d;color:#f8f8f2;padding:15px;border-radius:5px;overflow-x:auto;font-size:13px;max-height:300px;overflow-y:auto;}
        .status{display:inline-block;padding:5px 10px;border-radius:3px;font-weight:bold;font-size:14px;}
        .status.ok{background:#28a745;color:white;}
        .status.fail{background:#dc3545;color:white;}
        .btn{display:inline-block;padding:10px 20px;background:#dc3545;color:white;text-decoration:none;border-radius:5px;margin-top:20px;}
        .btn:hover{background:#c82333;}
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Autoload Script</h1>
        <p><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?></p>
        <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        
        <?php
        
        // Step 1: Check Environment
        echo '<div class="step info"><h3>📋 Step 1: Checking Environment</h3>';
        
        if (file_exists($basePath . '/composer.json')) {
            echo '<p>✓ composer.json found</p>';
            $results['composer'] = true;
        } else {
            echo '<p>✗ composer.json NOT found</p>';
            $results['composer'] = false;
        }
        
        if (is_dir($basePath . '/vendor')) {
            echo '<p>✓ vendor directory found</p>';
            $results['vendor'] = true;
        } else {
            echo '<p>✗ vendor directory NOT found</p>';
            $results['vendor'] = false;
        }
        
        if (file_exists($basePath . '/app/Helpers/helpers.php')) {
            echo '<p>✓ helpers.php found</p>';
            $results['helpers'] = true;
        } else {
            echo '<p>✗ helpers.php NOT found</p>';
            $results['helpers'] = false;
        }
        
        echo '</div>';
        
        // Step 2: Load Autoloader
        echo '<div class="step"><h3>🔄 Step 2: Loading Autoloader</h3>';
        
        try {
            require_once $basePath . '/vendor/autoload.php';
            echo '<p class="status ok">✓ Autoloader loaded</p>';
            $results['autoload'] = true;
        } catch (Exception $e) {
            echo '<p class="status fail">✗ Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            $results['autoload'] = false;
        }
        
        echo '</div>';
        
        // Step 3: Bootstrap Laravel & Clear Cache
        echo '<div class="step"><h3>🗑️ Step 3: Clearing Laravel Cache</h3>';
        
        try {
            $app = require_once $basePath . '/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            
            echo '<p>Clearing config cache...</p>';
            $kernel->call('config:clear');
            echo '<p class="status ok">✓ config:clear</p>';
            
            echo '<p>Clearing route cache...</p>';
            $kernel->call('route:clear');
            echo '<p class="status ok">✓ route:clear</p>';
            
            echo '<p>Clearing view cache...</p>';
            $kernel->call('view:clear');
            echo '<p class="status ok">✓ view:clear</p>';
            
            echo '<p>Clearing application cache...</p>';
            $kernel->call('cache:clear');
            echo '<p class="status ok">✓ cache:clear</p>';
            
            $results['cache'] = true;
            
        } catch (Exception $e) {
            echo '<p class="status fail">✗ ERROR: ' . htmlspecialchars($e->getMessage()) . '</p>';
            $results['cache'] = false;
        }
        
        echo '</div>';
        
        // Step 4: Verify Function
        echo '<div class="step"><h3>✅ Step 4: Verifying storage_url() Function</h3>';
        
        if (function_exists('storage_url')) {
            echo '<p class="status ok">✓ Function storage_url() EXISTS!</p>';
            
            try {
                $testUrl = storage_url('test.jpg');
                echo '<p>Test URL: <code>' . htmlspecialchars($testUrl) . '</code></p>';
                $results['function'] = true;
            } catch (Exception $e) {
                echo '<p class="warning">⚠ Function exists but error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                $results['function'] = true;
            }
        } else {
            echo '<p class="status fail">✗ Function storage_url() NOT FOUND</p>';
            $results['function'] = false;
        }
        
        echo '</div>';
        
        // Summary
        $allSuccess = !in_array(false, $results, true);
        
        echo '<div class="step ' . ($allSuccess ? 'success' : 'error') . '">';
        echo '<h3>📊 Summary</h3>';
        
        if ($allSuccess) {
            echo '<p><strong>✓ ALL STEPS COMPLETED SUCCESSFULLY!</strong></p>';
            echo '<p>The storage_url() function should now work on your website.</p>';
        } else {
            echo '<p><strong>⚠ SOME STEPS FAILED</strong></p>';
            echo '<p>Please contact your hosting provider or developer.</p>';
        }
        
        echo '<h4>Results:</h4><ul>';
        foreach ($results as $key => $value) {
            $icon = $value ? '✓' : '✗';
            $class = $value ? 'ok' : 'fail';
            echo '<li><span class="status ' . $class . '">' . $icon . '</span> ' . ucfirst($key) . '</li>';
        }
        echo '</ul></div>';
        
        ?>
        
        <div class="step warning">
            <h3>⚠️ SECURITY WARNING</h3>
            <p><strong>DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>
            <p>File location: <code>public/fix-autoload.php</code></p>
        </div>
        
        <div class="step info">
            <h3>📝 Next Steps</h3>
            <ol>
                <li>Test your website: <a href="/" target="_blank">Go to Homepage</a></li>
                <li>Check if the error is gone</li>
                <li><strong>DELETE this file (fix-autoload.php)</strong></li>
            </ol>
        </div>
        
        <a href="?password=<?php echo $password; ?>" class="btn">🔄 Run Again</a>
        
        <hr style="margin:30px 0;">
        <p style="text-align:center;color:#666;font-size:12px;">
            BPR SBabel - Fix Autoload Script<br>
            <?php echo date('Y-m-d H:i:s'); ?>
        </p>
    </div>
</body>
</html>
