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

        return view('admin.composer-update.index', compact('laravelVersion', 'phpVersion', 'composerVersion'));
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

            // Find composer path
            $composerPath = $this->findComposer();

            if (!$composerPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Composer tidak ditemukan di server.',
                    'output' => null,
                ]);
            }

            // Build the command
            $command = [$composerPath, 'update', '--no-interaction', '--prefer-dist'];

            // Add --no-dev if in production
            if (app()->environment('production')) {
                $command[] = '--no-dev';
            }

            $process = new Process($command, base_path(), null, null, $timeout);
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

    private function findComposer(): ?string
    {
        $paths = [
            'composer',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
            base_path('composer.phar'),
            'php ' . base_path('composer.phar'),
        ];

        foreach ($paths as $path) {
            $process = new Process([$path, '--version']);
            $process->run();
            if ($process->isSuccessful()) {
                return $path;
            }
        }

        return null;
    }

    private function getComposerVersion(): ?string
    {
        $composerPath = $this->findComposer();
        if (!$composerPath) {
            return 'Tidak ditemukan';
        }

        $process = new Process([$composerPath, '--version'], base_path());
        $process->run();

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            // Extract version from output like "Composer version 2.8.1 2024-11-01 10:21:22"
            if (preg_match('/Composer version ([^\s]+)/', $output, $matches)) {
                return $matches[1];
            }
        }

        return 'Tidak dapat dibaca';
    }
}
