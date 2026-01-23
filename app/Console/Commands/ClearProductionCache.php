<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearProductionCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'production:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Clearing Production Cache...');
        $this->newLine();

        // Clear application cache
        $this->info('1️⃣ Clearing application cache...');
        $this->call('cache:clear');
        
        // Clear config cache
        $this->info('2️⃣ Clearing config cache...');
        $this->call('config:clear');
        
        // Clear route cache
        $this->info('3️⃣ Clearing route cache...');
        $this->call('route:clear');
        
        // Clear view cache
        $this->info('4️⃣ Clearing view cache...');
        $this->call('view:clear');
        
        // Clear compiled classes
        $this->info('5️⃣ Clearing compiled classes...');
        $this->call('clear-compiled');
        
        // Optimize for production
        $this->newLine();
        $this->info('⚡ Optimizing for production...');
        $this->call('optimize');
        
        // Cache config
        $this->info('📦 Caching config...');
        $this->call('config:cache');
        
        // Cache routes
        $this->info('🛣️ Caching routes...');
        $this->call('route:cache');
        
        // Cache views
        $this->info('👁️ Caching views...');
        $this->call('view:cache');
        
        $this->newLine();
        $this->info('✅ Production cache cleared and optimized!');
        $this->newLine();
        
        // Show summary
        $this->table(
            ['Action', 'Status'],
            [
                ['Application Cache', '✅ Cleared'],
                ['Config Cache', '✅ Cleared & Cached'],
                ['Route Cache', '✅ Cleared & Cached'],
                ['View Cache', '✅ Cleared & Cached'],
                ['Compiled Classes', '✅ Cleared'],
                ['Optimization', '✅ Applied'],
            ]
        );
        
        $this->newLine();
        $this->warn('💡 Next steps:');
        $this->line('   1. Test the website in browser');
        $this->line('   2. Check: php artisan kas-keliling:debug');
        $this->line('   3. Monitor logs: tail -f storage/logs/laravel.log');
        
        return 0;
    }
}
