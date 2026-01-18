<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SwitchEnvironment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:switch {environment : The environment to switch to (local/production)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch application environment between local and production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $environment = $this->argument('environment');
        
        if (!in_array($environment, ['local', 'production'])) {
            $this->error('Invalid environment. Use "local" or "production".');
            return 1;
        }

        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            $this->error('.env file not found!');
            return 1;
        }

        $envContent = File::get($envPath);

        // Update APP_ENV
        $envContent = preg_replace('/^APP_ENV=.*/m', "APP_ENV={$environment}", $envContent);

        // Update APP_DEBUG
        $debug = $environment === 'local' ? 'true' : 'false';
        $envContent = preg_replace('/^APP_DEBUG=.*/m', "APP_DEBUG={$debug}", $envContent);

        // Update SESSION_SECURE_COOKIE
        $secureCookie = $environment === 'production' ? 'true' : 'false';
        if (preg_match('/^SESSION_SECURE_COOKIE=/m', $envContent)) {
            $envContent = preg_replace('/^SESSION_SECURE_COOKIE=.*/m', "SESSION_SECURE_COOKIE={$secureCookie}", $envContent);
        } else {
            $envContent .= "\nSESSION_SECURE_COOKIE={$secureCookie}";
        }

        File::put($envPath, $envContent);

        $this->info("Environment switched to: {$environment}");
        $this->newLine();

        // Show current configuration
        $this->table(
            ['Setting', 'Value'],
            [
                ['APP_ENV', $environment],
                ['APP_DEBUG', $debug],
                ['SESSION_SECURE_COOKIE', $secureCookie],
            ]
        );

        $this->newLine();
        $this->warn('Important: Clear cache to apply changes');
        
        if ($this->confirm('Do you want to clear cache now?', true)) {
            $this->call('config:clear');
            $this->call('cache:clear');
            $this->call('view:clear');
            
            if ($environment === 'production') {
                $this->info('Optimizing for production...');
                $this->call('config:cache');
                $this->call('route:cache');
                $this->call('view:cache');
            }
            
            $this->info('Cache cleared successfully!');
        }

        $this->newLine();
        
        if ($environment === 'production') {
            $this->warn('Production mode activated. Remember to:');
            $this->line('1. Update APP_URL in .env to your production domain');
            $this->line('2. Set secure database credentials');
            $this->line('3. Configure mail settings');
            $this->line('4. Run: php artisan storage:link');
        } else {
            $this->info('Local development mode activated.');
            $this->line('Storage URL will use: ' . config('app.url') . '/storage');
        }

        return 0;
    }
}
