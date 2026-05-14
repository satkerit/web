<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\SecurityLog;
use App\Models\SecuritySetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectSuspiciousActivity
{
    /**
     * SQL Injection patterns
     */
    protected array $sqlInjectionPatterns = [
        // UNION-based injection
        '/\bunion\s+(all\s+)?select\b/i',
        '/\bselect\b.+\bfrom\b.+\bwhere\b/i',
        '/\binsert\s+into\b/i',
        '/\bdelete\s+from\b/i',
        '/\bdrop\s+(table|database|index)\b/i',
        '/\btruncate\s+table\b/i',
        '/\balter\s+table\b/i',
        '/\bexec(\s+|\()xp_/i',
        '/\bexecute\s+immediate\b/i',

        // Boolean-based injection
        '/\'\s*(or|and)\s+[\'"]?\d+[\'"]?\s*=\s*[\'"]?\d+/i',
        '/\'\s*(or|and)\s+[\'"]?[a-z]+[\'"]?\s*=\s*[\'"]?[a-z]+/i',
        '/\bor\b\s+1\s*=\s*1/i',
        '/\band\b\s+1\s*=\s*1/i',

        // Time-based injection
        '/\bwaitfor\s+delay\b/i',
        '/\bbenchmark\s*\(/i',
        '/\bsleep\s*\(\s*\d+\s*\)/i',

        // Stacked queries
        '/;\s*(select|insert|update|delete|drop|create|alter|exec)/i',

        // Comment injection
        '/\/\*[\s\S]*?\*\//i',
        '/--\s*$/m',

        // Common payloads
        '/\b(char|nchar|varchar|nvarchar)\s*\(/i',
        '/\bconvert\s*\(/i',
        '/\bcast\s*\(/i',
        '/0x[0-9a-f]{2,}/i', // Hex encoded
    ];

    /**
     * XSS patterns
     */
    protected array $xssPatterns = [
        // Script tags
        '/<script[^>]*>.*?<\/script>/is',
        '/<script[^>]*>/i',

        // Event handlers
        '/\bon\w+\s*=\s*["\']?[^"\']+["\']?/i',

        // JavaScript protocol
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:[^,]*;base64/i',

        // SVG/Object injection
        '/<svg[^>]*onload/i',
        '/<object[^>]*data\s*=/i',
        '/<embed[^>]*src\s*=/i',
        '/<iframe[^>]*src\s*=/i',

        // Expression/eval
        '/expression\s*\(/i',
        '/eval\s*\(/i',

        // DOM manipulation
        '/document\s*\.\s*(cookie|write|location)/i',
        '/window\s*\.\s*(location|open)/i',
        '/innerHTML\s*=/i',

        // Encoded attacks
        '/&#x?[0-9a-f]+;/i',
        '/%3[cC]script/i',
    ];

    /**
     * Path traversal patterns
     */
    protected array $pathTraversalPatterns = [
        '/\.\.[\/\\\\]/i',
        '/\.\.%2[fF]/i',
        '/\.\.%5[cC]/i',
        '/%2e%2e[\/\\\\%]/i',
        '/\.\.\//i',
        '/\.\.\\\/i',

        // Specific file targets
        '/\/etc\/passwd/i',
        '/\/etc\/shadow/i',
        '/\/proc\/self/i',
        '/\/var\/log/i',
        '/c:\\\\windows/i',
        '/c:\\\\boot\.ini/i',
    ];

    /**
     * Command injection patterns
     */
    protected array $commandInjectionPatterns = [
        // '/[;&|`$]/', // Auto-removed: Too aggressive, blocks legitimate text like "Company & Co" or "$100"
        '/\$\(/i',
        '/`[^`]+`/',
        '/\|\s*\w+/',
        '/;\s*\w+/',
        '/\bwget\s+/i',
        '/\bcurl\s+/i',
        '/\bnc\s+-/i',
        '/\bnetcat\b/i',
        '/\bbash\s+-/i',
        '/\bsh\s+-c/i',
        '/\bperl\s+-e/i',
        '/\bpython\s+-c/i',
        '/\bphp\s+-r/i',
        '/\bruby\s+-e/i',
    ];

    /**
     * File inclusion patterns
     */
    protected array $fileInclusionPatterns = [
        '/\binclude\s*\(/i',
        '/\brequire\s*\(/i',
        '/\binclude_once\s*\(/i',
        '/\brequire_once\s*\(/i',
        '/\bfile_get_contents\s*\(/i',
        '/\bfopen\s*\(/i',
        '/\breadfile\s*\(/i',
        '/php:\/\/filter/i',
        '/php:\/\/input/i',
        '/expect:\/\//i',
        '/data:\/\//i',
    ];

    /**
     * Vulnerability scanner patterns (User-Agent)
     */
    protected array $scannerAgents = [
        'sqlmap',
        'nikto',
        'nmap',
        'masscan',
        'zgrab',
        'havij',
        'acunetix',
        'nessus',
        'openvas',
        'burpsuite',
        'w3af',
        'wpscan',
        'dirbuster',
        'gobuster',
        'ffuf',
        'nuclei',
        'httpx',
        'subfinder',
        'amass',
        'whatweb',
        'fierce',
    ];

    /**
     * Routes to exclude from checking
     */
    protected array $excludedRoutes = [
        // 'livewire/*', // Livewire should be checked
        '_ignition/*',
        'sanctum/*',
        'telescope/*',
        '__clockwork/*',
        // 'login', // Login should be checked for SQLi
        'logout',
        // 'register',
        // 'password/*',
    ];

    /**
     * Blocking configuration
     */
    protected int $blockThreshold = 5; // Block after 5 threats in 1 hour
    protected int $blockDurationHours = 24;

    public function handle(Request $request, Closure $next): Response
    {
        // Skip excluded routes
        if ($this->isExcludedRoute($request)) {
            return $next($request);
        }

        // We no longer bypass security checks for Super Admin automatically.
        // Even admins can be a source of malicious activity if account is compromised.
        // However, we can make it less aggressive for them if needed.

        $ip = $request->ip();

        // Check if IP is already blocked
        if ($this->isIpBlocked($ip)) {
            SecurityLog::logThreat($ip, 'blocked_ip', $request->fullUrl(), null, null, 'high', true);
            return $this->blockedResponse($request);
        }

        // Load settings
        $this->loadSettings();

        // Collect all inputs to check
        $inputsToCheck = $this->collectInputs($request);

        // Check for suspicious patterns
        $threat = $this->detectThreat($inputsToCheck, $request);

        if ($threat) {
            return $this->handleThreat($request, $ip, $threat);
        }

        // Check for suspicious user agent
        if ($this->isSuspiciousAgent($request)) {
            $threat = [
                'type' => 'scanner',
                'pattern' => 'Suspicious User Agent',
                'input' => $request->userAgent(),
            ];
            return $this->handleThreat($request, $ip, $threat);
        }

        return $next($request);
    }

    /**
     * Collect all inputs from request
     */
    protected function collectInputs(Request $request): array
    {
        $inputs = [];

        // URL path
        $inputs['url_path'] = urldecode($request->path());

        // Query string
        $inputs['query_string'] = urldecode($request->getQueryString() ?? '');

        // Request body (POST data)
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                $inputs["input_{$key}"] = $value;
            } elseif (is_array($value)) {
                $inputs["input_{$key}"] = json_encode($value);
            }
        }

        // Headers that might contain malicious content
        $inputs['referer'] = $request->header('Referer', '');
        $inputs['x_forwarded_for'] = $request->header('X-Forwarded-For', '');

        return array_filter($inputs);
    }

    /**
     * Detect threats in inputs
     */
    protected function detectThreat(array $inputs, Request $request): ?array
    {
        foreach ($inputs as $source => $value) {
            if (empty($value) || !is_string($value)) {
                continue;
            }

            // Check SQL Injection
            foreach ($this->sqlInjectionPatterns as $pattern) {
                if (@preg_match($pattern, $value)) {
                    return [
                        'type' => 'sql_injection',
                        'pattern' => $pattern,
                        'input' => $value,
                        'source' => $source,
                    ];
                }
            }

            // Check XSS
            foreach ($this->xssPatterns as $pattern) {
                if (@preg_match($pattern, $value)) {
                    return [
                        'type' => 'xss',
                        'pattern' => $pattern,
                        'input' => $value,
                        'source' => $source,
                    ];
                }
            }

            // Check Path Traversal
            foreach ($this->pathTraversalPatterns as $pattern) {
                if (@preg_match($pattern, $value)) {
                    return [
                        'type' => 'path_traversal',
                        'pattern' => $pattern,
                        'input' => $value,
                        'source' => $source,
                    ];
                }
            }

            // Check Command Injection (only in specific inputs, not URLs)
            if (str_starts_with($source, 'input_')) {
                foreach ($this->commandInjectionPatterns as $pattern) {
                    if (@preg_match($pattern, $value)) {
                        return [
                            'type' => 'command_injection',
                            'pattern' => $pattern,
                            'input' => $value,
                            'source' => $source,
                        ];
                    }
                }

                // Check File Inclusion
                foreach ($this->fileInclusionPatterns as $pattern) {
                    if (@preg_match($pattern, $value)) {
                        return [
                            'type' => 'file_inclusion',
                            'pattern' => $pattern,
                            'input' => $value,
                            'source' => $source,
                        ];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Handle detected threat
     */
    protected function handleThreat(Request $request, string $ip, array $threat): Response
    {
        // Log the threat
        SecurityLog::logThreat(
            $ip,
            $threat['type'],
            $request->fullUrl(),
            $threat['pattern'],
            $threat['input'],
            null,
            false
        );

        // Check if IP should be blocked
        $recentThreats = SecurityLog::getRecentThreatsByIp($ip, 60);

        if ($recentThreats >= $this->blockThreshold) {
            // Block the IP
            BlockedIp::blockIp($ip, "Auto-blocked: Multiple {$threat['type']} attempts ({$recentThreats}x)", $this->blockDurationHours);

            // Update log to mark as blocked
            SecurityLog::where('ip_address', $ip)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->update(['was_blocked' => true]);

            Log::alert('Security: IP auto-blocked for multiple attack attempts', [
                'ip' => $ip,
                'threat_type' => $threat['type'],
                'attempts' => $recentThreats,
            ]);
        }

        Log::warning('Security: Suspicious activity detected', [
            'ip' => $ip,
            'type' => $threat['type'],
            'url' => $request->fullUrl(),
            'pattern' => $threat['pattern'],
        ]);

        return $this->blockedResponse($request);
    }

    /**
     * Check if user agent is suspicious
     */
    protected function isSuspiciousAgent(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (empty($userAgent)) {
            return false;
        }

        foreach ($this->scannerAgents as $scanner) {
            if (str_contains($userAgent, strtolower($scanner))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is blocked
     */
    protected function isIpBlocked(string $ip): bool
    {
        try {
            return BlockedIp::isBlocked($ip);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if route is excluded
     */
    protected function isExcludedRoute(Request $request): bool
    {
        foreach ($this->excludedRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Load settings from database
     */
    protected function loadSettings(): void
    {
        try {
            $settings = Cache::remember('security_settings', 300, function () {
                return SecuritySetting::first();
            });

            if ($settings) {
                $this->blockThreshold = $settings->block_threshold ?? 5;
                $this->blockDurationHours = $settings->block_duration_hours ?? 24;
            }
        } catch (\Exception $e) {
            // Use defaults
        }
    }

    /**
     * Return blocked response
     */
    protected function blockedResponse(Request $request): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Your request has been flagged as suspicious.',
            ], 403);
        }

        abort(403, 'Access denied. Your request has been flagged as suspicious.');
    }
}
