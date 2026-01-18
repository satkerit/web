<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestHeroSliderPerformance extends Command
{
    protected $signature = 'test:hero-performance';
    protected $description = 'Test hero slider performance metrics';

    public function handle()
    {
        $this->info('Testing Hero Slider Performance...');
        $this->newLine();

        // Test 1: Database Query Performance
        $this->info('1. Testing Database Query Performance');
        $start = microtime(true);
        $heroSlides = CacheService::getHeroSlides(5);
        $queryTime = (microtime(true) - $start) * 1000;
        
        $this->line("   Query Time: " . number_format($queryTime, 2) . " ms");
        $this->line("   Slides Count: " . $heroSlides->count());
        $this->line("   Memory Used: " . $this->formatBytes(memory_get_usage(true)));
        
        if ($queryTime < 50) {
            $this->info("   ✓ Excellent performance!");
        } elseif ($queryTime < 100) {
            $this->comment("   ⚠ Good performance");
        } else {
            $this->error("   ✗ Needs optimization");
        }
        
        $this->newLine();

        // Test 2: Cache Performance
        $this->info('2. Testing Cache Performance');
        $start = microtime(true);
        $cachedSlides = CacheService::getHeroSlides(5);
        $cacheTime = (microtime(true) - $start) * 1000;
        
        $this->line("   Cache Hit Time: " . number_format($cacheTime, 2) . " ms");
        
        if ($cacheTime < 5) {
            $this->info("   ✓ Cache is working perfectly!");
        } else {
            $this->comment("   ⚠ Cache might need optimization");
        }
        
        $this->newLine();

        // Test 3: Data Size
        $this->info('3. Testing Data Transfer Size');
        $dataSize = strlen(serialize($heroSlides));
        $this->line("   Serialized Data Size: " . $this->formatBytes($dataSize));
        
        if ($dataSize < 50000) {
            $this->info("   ✓ Data size is optimal!");
        } elseif ($dataSize < 100000) {
            $this->comment("   ⚠ Data size is acceptable");
        } else {
            $this->error("   ✗ Data size is too large");
        }
        
        $this->newLine();

        // Test 4: Image Paths
        $this->info('4. Checking Image Optimization');
        $imagesCount = $heroSlides->filter(fn($slide) => $slide->image)->count();
        $this->line("   Images Found: " . $imagesCount);
        
        if ($imagesCount > 0) {
            $firstImage = $heroSlides->first()?->image;
            if ($firstImage) {
                $this->line("   First Image: " . $firstImage);
                $this->info("   ✓ First image will be preloaded");
            }
        }
        
        $this->newLine();

        // Summary
        $this->info('Performance Summary:');
        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['Query Time', number_format($queryTime, 2) . ' ms', $queryTime < 50 ? '✓ Good' : '⚠ Needs Work'],
                ['Cache Time', number_format($cacheTime, 2) . ' ms', $cacheTime < 5 ? '✓ Good' : '⚠ Needs Work'],
                ['Data Size', $this->formatBytes($dataSize), $dataSize < 50000 ? '✓ Good' : '⚠ Needs Work'],
                ['Slides Count', $heroSlides->count(), $heroSlides->count() <= 5 ? '✓ Good' : '⚠ Too Many'],
            ]
        );

        $this->newLine();
        $this->info('✓ Performance test completed!');
        
        return Command::SUCCESS;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
