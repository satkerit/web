<?php

use App\Models\Office;
use App\Services\CacheService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Js;

if (app()->environment('local')) {
    Route::get('/debug-office-form', function () {
        $office = Office::find(1);
        $lat = old('latitude', $office->latitude ?? '');
        $lng = old('longitude', $office->longitude ?? '');

        echo "<h2>Debug Office Form - Latitude/Longitude</h2>";
        echo "<pre>";
        echo "Raw latitude value: " . var_export($office->latitude, true) . "\n";
        echo "Raw longitude value: " . var_export($office->longitude, true) . "\n";
        echo "Type of latitude: " . gettype($office->latitude) . "\n";
        echo "Type of longitude: " . gettype($office->longitude) . "\n";
        echo "\n";
        echo "@js(lat) renders to: " . Js::from($lat ?: '') . "\n";
        echo "@js(lng) renders to: " . Js::from($lng ?: '') . "\n";
        echo "\n";

        $xdata = "mapPicker({ lat: " . Js::from($lat ?: '') . ", lng: " . Js::from($lng ?: '') . " })";
        echo "Full x-data attribute: " . htmlspecialchars($xdata) . "\n";
        echo "</pre>";
    });
}

// Secret cache clearing route (works on all environments with a token)
Route::get('/secret-clear-cache/{token}', function ($token) {
    // Replace 'YOUR_SECRET_TOKEN_HERE' with a strong secret token
    $expectedToken = config('app.secret_cache_token');

    if ($token !== $expectedToken) {
        abort(403, 'Unauthorized');
    }

    $output = [];

    try {
        // Clear all application caches
        Artisan::call('cache:clear');
        $output[] = '✅ Application cache cleared';

        Artisan::call('responsecache:clear');
        $output[] = '✅ Response cache cleared';

        Artisan::call('view:clear');
        $output[] = '✅ View cache cleared';

        Artisan::call('config:clear');
        $output[] = '✅ Config cache cleared';

        Artisan::call('route:clear');
        $output[] = '✅ Route cache cleared';

        // Clear report-specific cache
        CacheService::clearReportCache();
        $output[] = '✅ Report-specific cache cleared';
    } catch (\Exception $e) {
        $output[] = '❌ Error: ' . $e->getMessage();
    }

    echo "<h1>Cache Clearing Complete</h1>";
    echo "<ul>";
    foreach ($output as $line) {
        echo "<li>{$line}</li>";
    }
    echo "</ul>";
    echo "<p><strong>Important:</strong> Delete this route after use, or change the SECRET_CACHE_TOKEN in your .env file!</p>";
})->name('secret.clear-cache');
