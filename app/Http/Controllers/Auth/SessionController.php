<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        
        // Get settings from database to ensure consistency
        $settings = \App\Models\SecuritySetting::getSettings();
        $idleTimeout = $settings->idle_timeout ?: config('session.idle_timeout', 15);

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
        
        // Get settings from database to ensure consistency
        $settings = \App\Models\SecuritySetting::getSettings();
        $idleTimeout = $settings->idle_timeout ?: config('session.idle_timeout', 15);
        $idleTimeoutSeconds = $idleTimeout * 60; // in seconds
        
        $lastActivity = Cache::get($sessionKey, $currentTime);

        $timeRemaining = $idleTimeoutSeconds - ($currentTime - $lastActivity);

        return response()->json([
            'authenticated' => true,
            'last_activity' => $lastActivity,
            'current_time' => $currentTime,
            'idle_timeout' => $idleTimeoutSeconds,
            'time_remaining' => max(0, $timeRemaining),
            'expires_at' => $lastActivity + $idleTimeoutSeconds,
            'will_expire_soon' => $timeRemaining <= 300 // 5 minutes warning
        ]);
    }
}
