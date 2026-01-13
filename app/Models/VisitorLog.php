<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'country',
        'country_code',
        'city',
        'region',
        'timezone',
        'latitude',
        'longitude',
        'isp',
        'device_type',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'user_agent',
        'url',
        'referrer',
        'session_id'
    ];

    public static function logVisit(): ?self
    {
        $request = request();
        $ip = $request->ip();
        $sessionId = session()->getId();

        // Check if already logged in this session for this URL
        $exists = self::where('session_id', $sessionId)
            ->where('url', $request->fullUrl())
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if ($exists) {
            return null;
        }

        $userAgent = $request->userAgent();
        $deviceInfo = self::parseUserAgent($userAgent);
        $geoInfo = self::getGeoInfo($ip);

        return self::create([
            'ip_address' => $ip,
            'country' => $geoInfo['country'] ?? null,
            'country_code' => $geoInfo['country_code'] ?? null,
            'city' => $geoInfo['city'] ?? null,
            'region' => $geoInfo['region'] ?? null,
            'timezone' => $geoInfo['timezone'] ?? null,
            'latitude' => $geoInfo['latitude'] ?? null,
            'longitude' => $geoInfo['longitude'] ?? null,
            'isp' => $geoInfo['isp'] ?? null,
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'browser_version' => $deviceInfo['browser_version'],
            'platform' => $deviceInfo['platform'],
            'platform_version' => $deviceInfo['platform_version'],
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'referrer' => $request->header('referer'),
            'session_id' => $sessionId
        ]);
    }

    protected static function parseUserAgent(?string $userAgent): array
    {
        $result = [
            'device_type' => 'desktop',
            'browser' => 'Unknown',
            'browser_version' => null,
            'platform' => 'Unknown',
            'platform_version' => null
        ];

        if (!$userAgent) return $result;

        // Device type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent)) {
            $result['device_type'] = preg_match('/iPad|Tablet/i', $userAgent) ? 'tablet' : 'mobile';
        }

        // Browser
        if (preg_match('/Edge\/(\d+)/i', $userAgent, $m)) {
            $result['browser'] = 'Edge';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Chrome\/(\d+)/i', $userAgent, $m)) {
            $result['browser'] = 'Chrome';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Firefox\/(\d+)/i', $userAgent, $m)) {
            $result['browser'] = 'Firefox';
            $result['browser_version'] = $m[1];
        } elseif (preg_match('/Safari\/(\d+)/i', $userAgent, $m) && !preg_match('/Chrome/i', $userAgent)) {
            $result['browser'] = 'Safari';
            $result['browser_version'] = $m[1];
        }

        // Platform
        if (preg_match('/Windows NT (\d+\.\d+)/i', $userAgent, $m)) {
            $result['platform'] = 'Windows';
            $result['platform_version'] = $m[1];
        } elseif (preg_match('/Mac OS X (\d+[._]\d+)/i', $userAgent, $m)) {
            $result['platform'] = 'macOS';
            $result['platform_version'] = str_replace('_', '.', $m[1]);
        } elseif (preg_match('/Android (\d+)/i', $userAgent, $m)) {
            $result['platform'] = 'Android';
            $result['platform_version'] = $m[1];
        } elseif (preg_match('/iPhone OS (\d+)/i', $userAgent, $m)) {
            $result['platform'] = 'iOS';
            $result['platform_version'] = $m[1];
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $result['platform'] = 'Linux';
        }

        return $result;
    }

    protected static function getGeoInfo(string $ip): array
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['country' => 'Local', 'country_code' => 'LO', 'city' => 'Local'];
        }

        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,region,city,timezone,lat,lon,isp");
            if ($response) {
                $data = json_decode($response, true);
                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'country_code' => $data['countryCode'] ?? null,
                        'city' => $data['city'] ?? null,
                        'region' => $data['region'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null,
                        'isp' => $data['isp'] ?? null
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return [];
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}
