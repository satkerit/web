<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SessionController extends Controller
{
    /**
     * Update user activity timestamp
     */
    public function updateActivity(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;
        $idleTimeout = Config::get('security.idle_timeout', 30);

        // Update last activity time
        Cache::put($sessionKey, $currentTime, now()->addMinutes($idleTimeout + 10));

        return response()->json([
            'success' => true,
            'last_activity' => $currentTime,
            'idle_timeout' => $idleTimeout * 60, // in seconds
            'expires_at' => $currentTime + ($idleTimeout * 60)
        ]);
    }

    /**
     * Get session status
     */
    public function getStatus(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;
        $idleTimeout = Config::get('security.idle_timeout', 30) * 60; // in seconds
        $lastActivity = Cache::get($sessionKey, $currentTime);

        $timeRemaining = $idleTimeout - ($currentTime - $lastActivity);

        return response()->json([
            'authenticated' => true,
            'last_activity' => $lastActivity,
            'current_time' => $currentTime,
            'idle_timeout' => $idleTimeout,
            'time_remaining' => max(0, $timeRemaining),
            'expires_at' => $lastActivity + $idleTimeout,
            'will_expire_soon' => $timeRemaining <= 300 // 5 minutes warning
        ]);
    }
}
