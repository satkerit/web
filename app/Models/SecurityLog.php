<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class SecurityLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'payload',
        'threat_type',
        'threat_level',
        'matched_pattern',
        'raw_input',
        'user_id',
        'country_code',
        'session_id',
        'was_blocked',
    ];

    protected $casts = [
        'was_blocked' => 'boolean',
        'payload' => 'array',
    ];

    /**
     * Threat level colors for UI
     */
    public const THREAT_LEVELS = [
        'low' => ['color' => 'blue', 'label' => 'Rendah'],
        'medium' => ['color' => 'yellow', 'label' => 'Sedang'],
        'high' => ['color' => 'orange', 'label' => 'Tinggi'],
        'critical' => ['color' => 'red', 'label' => 'Kritis'],
    ];

    /**
     * Threat types with descriptions
     */
    public const THREAT_TYPES = [
        'sql_injection' => ['label' => 'SQL Injection', 'icon' => 'database', 'level' => 'critical'],
        'xss' => ['label' => 'Cross-Site Scripting (XSS)', 'icon' => 'code', 'level' => 'high'],
        'path_traversal' => ['label' => 'Path Traversal', 'icon' => 'folder', 'level' => 'high'],
        'command_injection' => ['label' => 'Command Injection', 'icon' => 'terminal', 'level' => 'critical'],
        'file_inclusion' => ['label' => 'File Inclusion', 'icon' => 'file', 'level' => 'critical'],
        'brute_force' => ['label' => 'Brute Force', 'icon' => 'key', 'level' => 'medium'],
        'scanner' => ['label' => 'Vulnerability Scanner', 'icon' => 'search', 'level' => 'medium'],
        'suspicious_agent' => ['label' => 'Suspicious User Agent', 'icon' => 'user-x', 'level' => 'low'],
        'rate_limit' => ['label' => 'Rate Limit Exceeded', 'icon' => 'clock', 'level' => 'medium'],
        'blocked_ip' => ['label' => 'Blocked IP Access', 'icon' => 'shield-off', 'level' => 'high'],
        'unknown' => ['label' => 'Unknown Threat', 'icon' => 'alert-triangle', 'level' => 'medium'],
    ];

    /**
     * Relationship to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a security event
     */
    public static function logThreat(
        string $ip,
        string $threatType,
        string $requestUrl,
        ?string $matchedPattern = null,
        ?string $rawInput = null,
        ?string $threatLevel = null,
        bool $wasBlocked = false
    ): self {
        $threatLevel = $threatLevel ?? (self::THREAT_TYPES[$threatType]['level'] ?? 'medium');

        $log = self::create([
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => substr($requestUrl, 0, 2048),
            'payload' => self::sanitizePayload(request()->all()),
            'threat_type' => $threatType,
            'threat_level' => $threatLevel,
            'matched_pattern' => $matchedPattern ? substr($matchedPattern, 0, 1000) : null,
            'raw_input' => $rawInput ? substr($rawInput, 0, 5000) : null,
            'user_id' => auth()->id(),
            'country_code' => self::getCountryFromIp($ip),
            'session_id' => session()->getId(),
            'was_blocked' => $wasBlocked,
        ]);

        // Update cache counters
        self::incrementTodayCount();

        return $log;
    }

    /**
     * Sanitize payload to remove sensitive data
     */
    protected static function sanitizePayload(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'api_key', 'secret', '_token'];

        foreach ($data as $key => $value) {
            // Filter out files/UploadedFile objects as they cannot be JSON encoded
            if ($value instanceof \Illuminate\Http\UploadedFile || (is_array($value) && isset($value[0]) && $value[0] instanceof \Illuminate\Http\UploadedFile)) {
                $data[$key] = '[FILE_UPLOAD]';
                continue;
            }

            if (in_array(strtolower($key), $sensitiveKeys)) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = self::sanitizePayload($value);
            } elseif (is_string($value) && strlen($value) > 500) {
                $data[$key] = substr($value, 0, 500) . '... [TRUNCATED]';
            }
        }

        return $data;
    }

    /**
     * Get country from IP (placeholder - can integrate with GeoIP service)
     */
    protected static function getCountryFromIp(string $ip): ?string
    {
        // For local/private IPs
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'LOCAL';
        }

        // Can integrate with MaxMind GeoIP2 or similar service here
        return null;
    }

    /**
     * Increment today's threat count
     */
    protected static function incrementTodayCount(): void
    {
        $key = 'security_threats_' . date('Y-m-d');
        Cache::increment($key);
        Cache::put('security_threats_today', Cache::get($key, 0), now()->endOfDay());
    }

    /**
     * Get today's threat count
     */
    public static function getTodayCount(): int
    {
        return Cache::get('security_threats_' . date('Y-m-d'), 0);
    }

    /**
     * Get threats by IP in last hour
     */
    public static function getRecentThreatsByIp(string $ip, int $minutes = 60): int
    {
        return self::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Get threat statistics
     */
    public static function getStatistics(int $days = 7): array
    {
        $startDate = now()->subDays($days)->startOfDay();

        return [
            'total' => self::where('created_at', '>=', $startDate)->count(),
            'blocked' => self::where('created_at', '>=', $startDate)->where('was_blocked', true)->count(),
            'by_type' => self::where('created_at', '>=', $startDate)
                ->selectRaw('threat_type, COUNT(*) as count')
                ->groupBy('threat_type')
                ->pluck('count', 'threat_type')
                ->toArray(),
            'by_level' => self::where('created_at', '>=', $startDate)
                ->selectRaw('threat_level, COUNT(*) as count')
                ->groupBy('threat_level')
                ->pluck('count', 'threat_level')
                ->toArray(),
            'top_ips' => self::where('created_at', '>=', $startDate)
                ->selectRaw('ip_address, COUNT(*) as count')
                ->groupBy('ip_address')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'ip_address')
                ->toArray(),
            'daily_trend' => self::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date')
                ->toArray(),
        ];
    }

    /**
     * Cleanup old logs
     */
    public static function cleanup(int $daysToKeep = 30): int
    {
        return self::where('created_at', '<', now()->subDays($daysToKeep))->delete();
    }

    /**
     * Get threat level badge class for UI
     */
    public function getThreatBadgeClass(): string
    {
        return match ($this->threat_level) {
            'critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'low' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    /**
     * Get threat type info
     */
    public function getThreatInfo(): array
    {
        return self::THREAT_TYPES[$this->threat_type] ?? self::THREAT_TYPES['unknown'];
    }
}
