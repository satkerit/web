<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class ClearHomeCache extends Command
{
    protected $signature = 'cache:clear-frontend {--all : Clear all frontend caches}';
    protected $description = 'Clear frontend page caches';

    public function handle(): int
    {
        if ($this->option('all')) {
            CacheService::clearAll();
            $this->info('All frontend caches cleared successfully!');
        } else {
            // Clear only home page caches
            CacheService::clear('company_info');
            CacheService::clear('hero_slides_5');
            CacheService::clear('products_home_6');
            CacheService::clear('news_home_3');
            CacheService::clear('auctions_home_3');
            $this->info('Home page cache cleared successfully!');
        }

        return self::SUCCESS;
    }
}
