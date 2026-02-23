<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use App\Services\CacheService;
use Illuminate\Console\Command;

class UpdateHeroSlideLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hero-slide:limit {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update hero slide limit configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = (int) $this->argument('limit');

        if ($limit < 1 || $limit > 20) {
            $this->error('Limit must be between 1 and 20');
            return Command::FAILURE;
        }

        $this->info('Updating hero slide limit to: ' . $limit);

        try {
            $settings = SiteSetting::getSettings();
            $settings->update(['hero_slide_limit' => $limit]);

            // Clear hero slides cache
            for ($i = 1; $i <= 20; $i++) {
                CacheService::clear("hero_slides_{$i}");
            }

            $this->info('✅ Hero slide limit updated successfully');
            $this->line('New limit: ' . $limit);

            // Test the update
            $heroSlides = CacheService::getHeroSlidesDynamic();
            $this->line('Retrieved slides: ' . $heroSlides->count());
            $this->line('Max allowed: ' . $limit);

            if ($heroSlides->count() > 0) {
                $this->info('✅ Cache working correctly');
            } else {
                $this->warn('⚠ No hero slides found in database');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to update hero slide limit: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}