<?php

use App\Models\Office;
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
