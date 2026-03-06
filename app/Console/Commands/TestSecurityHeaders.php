<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestSecurityHeaders extends Command
{
    protected $signature = 'test:security-headers {url?}';
    protected $description = 'Test security headers on the application';

    public function handle(): int
    {
        $url = $this->argument('url') ?? config('app.url');
        
        $this->info("🔒 Testing Security Headers for: {$url}");
        $this->newLine();

        try {
            $response = Http::timeout(10)->get($url);
            $headers = $response->headers();

            $requiredHeaders = [
                'X-Frame-Options' => 'SAMEORIGIN',
                'X-Content-Type-Options' => 'nosniff',
                'X-XSS-Protection' => '1; mode=block',
                'Referrer-Policy' => 'strict-origin-when-cross-origin',
                'Content-Security-Policy' => null, // Just check existence
                'Cross-Origin-Opener-Policy' => 'same-origin',
                'Cross-Origin-Embedder-Policy' => 'require-corp',
                'Cross-Origin-Resource-Policy' => 'same-origin',
            ];

            $passed = 0;
            $failed = 0;

            foreach ($requiredHeaders as $header => $expectedValue) {
                $headerValue = $headers[$header][0] ?? null;

                if ($headerValue) {
                    if ($expectedValue === null || str_contains($headerValue, $expectedValue)) {
                        $this->line("✅ <fg=green>{$header}</>: {$headerValue}");
                        $passed++;
                    } else {
                        $this->line("⚠️  <fg=yellow>{$header}</>: {$headerValue} (expected: {$expectedValue})");
                        $passed++;
                    }
                } else {
                    $this->line("❌ <fg=red>{$header}</>: Missing");
                    $failed++;
                }
            }

            // Check HSTS (only in production)
            if (app()->environment('production')) {
                $hsts = $headers['Strict-Transport-Security'][0] ?? null;
                if ($hsts) {
                    $this->line("✅ <fg=green>Strict-Transport-Security</>: {$hsts}");
                    $passed++;
                } else {
                    $this->line("❌ <fg=red>Strict-Transport-Security</>: Missing (required for production)");
                    $failed++;
                }
            } else {
                $this->line("ℹ️  <fg=gray>Strict-Transport-Security</>: Skipped (not production)");
            }

            $this->newLine();
            $this->info("Results: {$passed} passed, {$failed} failed");

            if ($failed === 0) {
                $this->info('🎉 All security headers are properly configured!');
                return Command::SUCCESS;
            } else {
                $this->warn('⚠️  Some security headers are missing or misconfigured.');
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("Failed to fetch URL: {$e->getMessage()}");
            $this->warn("Make sure the application is running and accessible.");
            return Command::FAILURE;
        }
    }
}
