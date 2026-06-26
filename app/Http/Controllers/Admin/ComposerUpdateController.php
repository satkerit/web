<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\AuthorizesAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ComposerUpdateController extends Controller
{
    use AuthorizesAdminActions;

    public function index()
    {
        $this->authorizeAny(['settings.composer']);

        // Get current Laravel and PHP versions
        $laravelVersion = app()->version();
        $phpVersion = phpversion();
        $composerVersion = $this->getComposerVersion();
        $nonce = request()->attributes->get('csp_nonce');

        // Check if proc_open is available
        $procOpenAvailable = function_exists('proc_open');

        // Get PHP binary path
        $phpBinary = PHP_BINARY;

        // Get composer command suggestions
        $composerCommands = [];
        $composerPharPath = base_path('composer.phar');

        if (file_exists($composerPharPath)) {
            $composerCommands[] = "$phpBinary -d memory_limit=-1 composer.phar update --no-interaction --prefer-dist" . (app()->environment('production') ? ' --no-dev' : '');
        }

        $composerCommands[] = "composer update --no-interaction --prefer-dist" . (app()->environment('production') ? ' --no-dev' : '');
        $composerCommands[] = "/usr/local/bin/composer update --no-interaction --prefer-dist" . (app()->environment('production') ? ' --no-dev' : '');
        $composerCommands[] = "/usr/bin/composer update --no-interaction --prefer-dist" . (app()->environment('production') ? ' --no-dev' : '');

        return view('admin.composer-update.index', compact('laravelVersion', 'phpVersion', 'composerVersion', 'procOpenAvailable', 'composerCommands'));
    }

    public function runUpdate(Request $request)
    {
        $this->authorizeAny(['settings.composer']);

        // Validate CSRF and run update
        $request->validate([
            'confirm' => 'required|accepted',
        ]);

        try {
            // Check if proc_open is available
            if (!function_exists('proc_open')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fungsi proc_open tidak tersedia di server. Anda harus menjalankan composer update secara manual via SSH atau panel hosting.',
                    'output' => null,
                ]);
            }

            // Increase maximum execution time to 10 minutes
            set_time_limit(600);
            ini_set('max_execution_time', 600);
            ini_set('memory_limit', '512M');

            // Set timeout to 10 minutes (600 seconds)
            $timeout = 600;

            // Get PHP binary path
            $phpBinary = PHP_BINARY;

            // Check if composer.phar exists in project root first
            $composerPharPath = base_path('composer.phar');

            // Find composer command parts
            $composerCommand = null;
            $paths = [
                [$phpBinary, '-d', 'memory_limit=-1', 'composer', 'update', '--no-interaction', '--prefer-dist'],
                [$phpBinary, '-d', 'memory_limit=-1', '/usr/local/bin/composer', 'update', '--no-interaction', '--prefer-dist'],
                [$phpBinary, '-d', 'memory_limit=-1', '/usr/bin/composer', 'update', '--no-interaction', '--prefer-dist'],
                [$phpBinary, '-d', 'memory_limit=-1', '/opt/cpanel/composer/bin/composer', 'update', '--no-interaction', '--prefer-dist'], // cPanel
                [$phpBinary, '-d', 'memory_limit=-1', '/opt/alt/php' . PHP_MAJOR_VERSION . PHP_MINOR_VERSION . '/usr/bin/composer', 'update', '--no-interaction', '--prefer-dist'], // CloudLinux/alt-php
            ];

            // Add composer.phar if it exists
            if (file_exists($composerPharPath)) {
                array_unshift($paths, [$phpBinary, '-d', 'memory_limit=-1', $composerPharPath, 'update', '--no-interaction', '--prefer-dist']);
            }

            // Check which one works
            foreach ($paths as $cmdParts) {
                try {
                    $testCmdParts = array_slice($cmdParts, 0, count($cmdParts) - 3); // Remove update params
                    $testCmdParts[] = '--version';
                    $testProcess = new Process($testCmdParts, base_path());
                    $testProcess->setTimeout(15);
                    $testProcess->run();
                    if ($testProcess->isSuccessful()) {
                        $composerCommand = $cmdParts;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (!$composerCommand) {
                $message = 'Composer tidak ditemukan di server. ';
                if (!file_exists($composerPharPath)) {
                    $message .= 'Silakan upload composer.phar ke direktori root project Anda. ';
                }
                $message .= 'Atau jalankan composer update secara manual via SSH atau panel hosting.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'output' => null,
                ]);
            }

            // Add --no-dev if in production
            if (app()->environment('production')) {
                $composerCommand[] = '--no-dev';
            }

            $process = new Process($composerCommand, base_path(), null, null, $timeout);
            $process->start();

            $output = '';
            $process->wait(function ($type, $buffer) use (&$output) {
                $output .= $buffer;
            });

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Preserve user's last activity cache before clearing all caches
            $userId = auth()->id();
            $sessionKey = 'user_last_activity_' . $userId;
            $lastActivity = \Illuminate\Support\Facades\Cache::get($sessionKey);

            // Clear caches after update
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');

            // Clear cache but preserve user's last activity
            // Instead of clearing all cache, clear only specific cache tags or use selective clearing
            // Since we're using file cache, let's clear cache then restore user's last activity
            Artisan::call('cache:clear');

            // Restore user's last activity cache
            if ($lastActivity) {
                $idleTimeout = (int) config('session.idle_timeout', 15);
                $cacheDuration = now()->addMinutes($idleTimeout + 10);
                \Illuminate\Support\Facades\Cache::put($sessionKey, $lastActivity, $cacheDuration);
            }

            return response()->json([
                'success' => true,
                'message' => 'Composer update berhasil!',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'output' => $e->getTraceAsString(),
            ], 500);
        }
    }

    private function getComposerVersion(): ?string
    {
        try {
            // Get PHP binary path
            $phpBinary = PHP_BINARY;

            // Check if composer.phar exists in project root first
            $composerPharPath = base_path('composer.phar');

            $paths = [
                [$phpBinary, '-d', 'memory_limit=-1', 'composer', '--version'],
                [$phpBinary, '-d', 'memory_limit=-1', '/usr/local/bin/composer', '--version'],
                [$phpBinary, '-d', 'memory_limit=-1', '/usr/bin/composer', '--version'],
                [$phpBinary, '-d', 'memory_limit=-1', '/opt/cpanel/composer/bin/composer', '--version'], // cPanel
                [$phpBinary, '-d', 'memory_limit=-1', '/opt/alt/php' . PHP_MAJOR_VERSION . PHP_MINOR_VERSION . '/usr/bin/composer', '--version'], // CloudLinux/alt-php
            ];

            // Add composer.phar if it exists
            if (file_exists($composerPharPath)) {
                array_unshift($paths, [$phpBinary, '-d', 'memory_limit=-1', $composerPharPath, '--version']);
            }

            foreach ($paths as $cmdParts) {
                try {
                    $process = new Process($cmdParts, base_path());
                    $process->setTimeout(15);
                    $process->run();

                    if ($process->isSuccessful()) {
                        $output = $process->getOutput();
                        // Extract version from output like "Composer version 2.8.1 2024-11-01 10:21:22"
                        if (preg_match('/Composer version ([^\s]+)/', $output, $matches)) {
                            return $matches[1];
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }

        return 'Tidak dapat dibaca';
    }
}
