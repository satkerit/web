<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Models\AuditTrail;

class SessionController extends Controller
{
    /**
     * Extend user session
     */
    public function extend(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $user = Auth::user();
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;
        $idleTimeout = Config::get('security.idle_timeout', 30);

        // Update last activity time
        Cache::put($sessionKey, $currentTime, now()->addMinutes($idleTimeout + 10));

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Log session extension
        AuditTrail::log('session_extended', 'User extended session: ' . $user->name);

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil diperpanjang',
            'extended_until' => now()->addMinutes($idleTimeout)->format('Y-m-d H:i:s'),
            'idle_timeout' => $idleTimeout * 60, // in seconds
            'current_time' => $currentTime
        ]);
    }

    /**
     * Get current session status
     */
    public function status(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'authenticated' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $user = Auth::user();
        $sessionKey = 'user_last_activity_' . $user->id;
        $currentTime = now()->timestamp;
        $lastActivity = Cache::get($sessionKey, $currentTime);
        $idleTimeout = Config::get('security.idle_timeout', 30) * 60; // Convert to seconds

        $timeIdle = $currentTime - $lastActivity;
        $timeRemaining = max(0, $idleTimeout - $timeIdle);

        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'session' => [
                'last_activity' => $lastActivity,
                'current_time' => $currentTime,
                'time_idle' => $timeIdle,
                'time_remaining' => $timeRemaining,
                'idle_timeout' => $idleTimeout,
                'will_expire_at' => date('Y-m-d H:i:s', $lastActivity + $idleTimeout),
                'is_warning_time' => $timeRemaining <= (Config::get('session.idle_warning', 5) * 60)
            ]
        ]);
    }
}
