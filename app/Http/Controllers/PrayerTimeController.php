<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimeController extends Controller
{
    /**
     * Get prayer times for a specific location
     */
    public function getPrayerTimes(Request $request)
    {
        $latitude = $request->input('latitude', -6.2088); // Default: Jakarta
        $longitude = $request->input('longitude', 106.8456);
        $method = $request->input('method', 2); // 2 = ISNA (Islamic Society of North America)
        
        // Cache key based on location and date
        $cacheKey = "prayer_times_{$latitude}_{$longitude}_" . date('Y-m-d');
        
        // Cache for 1 day
        $prayerTimes = Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude, $method) {
            try {
                $response = Http::timeout(10)->get('http://api.aladhan.com/v1/timings', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'method' => $method,
                    'timestamp' => time(),
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'timings' => $data['data']['timings'] ?? [],
                        'date' => $data['data']['date'] ?? [],
                    ];
                }

                return ['success' => false, 'message' => 'Failed to fetch prayer times'];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        });

        return response()->json($prayerTimes);
    }
}
