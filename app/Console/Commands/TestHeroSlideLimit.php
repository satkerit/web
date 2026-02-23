<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Services\CacheService;
use Illuminate\Console\Command;

class TestHeroSlideLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:hero-slide-limit {limit?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test hero slide limit configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing Hero Slide Limit Configuration');
        $this->newLine();

        // Get current settings
        $settings = SiteSetting::getSettings();
        $this->info('Current Settings:');
        $this->line('  Hero Slide Limit: ' . ($settings->hero_slide_limit ?? 5));
        $this->line('  Hero Slider Delay: ' . ($settings->hero_slider_delay ?? 5000) . 'ms');
        $this->newLine();

        // Test dynamic retrieval
        $this->info('Testing Dynamic Hero Slides Retrieval:');
        $heroSlides = CacheService::getHeroSlidesDynamic();
        $this->line('  Retrieved Slides: ' . $heroSlides->count());
        $this->line('  Max Allowed: ' . ($settings->hero_slide_limit ?? 5));

        if ($heroSlides->count() > 0) {
            $this->info('  ✓ Successfully retrieved hero slides');
            
            // Display first slide info
            $firstSlide = $heroSlides->first();
            $this->line('  First Slide: ' . ($firstSlide->title ?? 'No title'));
            $this->line('  Image: ' . ($firstSlide->image ?? 'No image'));
        } else {
            $this->warn('  ⚠ No hero slides found');
            $this->line('  Tip: Make sure you have active hero slides in the database');
        }

        // Test with custom limit if provided
        $limit = $this->argument('limit');
        if ($limit !== null) {
            $this->newLine();
            $this->info('Testing with custom limit: ' . $limit);
            
            $limit = (int) $limit;
            if ($limit >= 1 && $limit <= 20) {
                $customSlides = CacheService::getHeroSlides($limit);
                $this->line('  Retrieved Slides: ' . $customSlides->count());
                $this->line('  Requested Limit: ' . $limit);
                
                if ($customSlides->count() > 0) {
                    $this->info('  ✓ Custom limit test successful');
                } else {
                    $this->warn('  ⚠ No slides retrieved with custom limit');
                }
            } else {
                $this->error('  ✗ Invalid limit. Must be between 1-20');
            }
        }

        // Test cache clearing
        $this->newLine();
        $this->info('Testing Cache Clearing:');
        CacheService::clearAll();
        $this->info('  ✓ All caches cleared successfully');

        $this->newLine();
        $this->info('✅ Hero slide limit test completed!');

        return Command::SUCCESS;
    }
}