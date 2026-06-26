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

        return view('admin.composer-update.index', compact('laravelVersion', 'phpVersion', 'composerVersion', 'nonce'));
    }

    public function runUpdate(Request $request)
    {
        $this->authorizeAny(['settings.composer']);

        // Validate CSRF and run update
        $request->validate([
            'confirm' => 'required|accepted',
        ]);

        try {
            // Set timeout to 5 minutes (300 seconds)
            $timeout = 300;

            // Find composer command parts
            $composerCommand = null;
            $paths = [
                ['composer', 'update', '--no-interaction', '--prefer-dist'],
                ['/usr/local/bin/composer', 'update', '--no-interaction', '--prefer-dist'],
                ['/usr/bin/composer', 'update', '--no-interaction', '--prefer-dist'],
                ['php', base_path('composer.phar'), 'update', '--no-interaction', '--prefer-dist'],
            ];

            // Check which one works
            foreach ($paths as $cmdParts) {
                try {
                    $testProcess = new Process([$cmdParts[0], '--version'], base_path());
                    $testProcess->setTimeout(10);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Composer tidak ditemukan di server.',
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

            // Clear caches after update
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

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
            $paths = [
                ['composer', '--version'],
                ['/usr/local/bin/composer', '--version'],
                ['/usr/bin/composer', '--version'],
                ['php', base_path('composer.phar'), '--version'],
            ];

            foreach ($paths as $cmdParts) {
                try {
                    $process = new Process($cmdParts, base_path());
                    $process->setTimeout(10);
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
