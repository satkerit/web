<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\News;
use App\Services\ImageCompressionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateResponsiveImages extends Command
{
    protected $signature = 'images:generate-responsive 
                            {--model= : Model to process (hero, product, news, all)}
                            {--force : Force regenerate even if variants exist}';

    protected $description = 'Generate responsive WebP and compressed versions for existing images';

    public function handle()
    {
        $model = $this->option('model') ?? 'all';
        $force = $this->option('force');

        $this->info('🚀 Starting responsive image generation...');
        $this->newLine();

        $stats = [
            'processed' => 0,
            'skipped' => 0,
            'failed' => 0
        ];

        if ($model === 'all' || $model === 'hero') {
            $this->info('📸 Processing Hero Slides...');
            $heroStats = $this->processHeroSlides($force);
            $stats['processed'] += $heroStats['processed'];
            $stats['skipped'] += $heroStats['skipped'];
            $stats['failed'] += $heroStats['failed'];
        }

        if ($model === 'all' || $model === 'product') {
            $this->info('📦 Processing Products...');
            $productStats = $this->processProducts($force);
            $stats['processed'] += $productStats['processed'];
            $stats['skipped'] += $productStats['skipped'];
            $stats['failed'] += $productStats['failed'];
        }

        if ($model === 'all' || $model === 'news') {
            $this->info('📰 Processing News...');
            $newsStats = $this->processNews($force);
            $stats['processed'] += $newsStats['processed'];
            $stats['skipped'] += $newsStats['skipped'];
            $stats['failed'] += $newsStats['failed'];
        }

        $this->newLine();
        $this->info('✅ Generation completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Processed', $stats['processed']],
                ['Skipped', $stats['skipped']],
                ['Failed', $stats['failed']],
            ]
        );

        return Command::SUCCESS;
    }

    protected function processHeroSlides(bool $force): array
    {
        $stats = ['processed' => 0, 'skipped' => 0, 'failed' => 0];
        
        $slides = HeroSlide::whereNotNull('image')->get();
        $bar = $this->output->createProgressBar($slides->count());
        $bar->start();

        foreach ($slides as $slide) {
            if (!$slide->image) {
                $stats['skipped']++;
                $bar->advance();
                continue;
            }

            if (!Storage::disk('public')->exists($slide->image)) {
                $this->warn("\n⚠️  Image not found: {$slide->image}");
                $stats['failed']++;
                $bar->advance();
                continue;
            }

            // Check if variants already exist
            if (!$force) {
                $webpVersions = ImageCompressionService::getExistingResponsiveWebP($slide->image);
                if (!empty($webpVersions)) {
                    $stats['skipped']++;
                    $bar->advance();
                    continue;
                }
            }

            try {
                ImageCompressionService::processUploadedImage($slide->image, [
                    'quality' => 85,
                    'webp_quality' => 85
                ]);
                $stats['processed']++;
            } catch (\Exception $e) {
                $this->error("\n❌ Failed: {$slide->image} - {$e->getMessage()}");
                $stats['failed']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $stats;
    }

    protected function processProducts(bool $force): array
    {
        $stats = ['processed' => 0, 'skipped' => 0, 'failed' => 0];
        
        $products = Product::whereNotNull('image')->get();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            if (!$product->image) {
                $stats['skipped']++;
                $bar->advance();
                continue;
            }

            if (!Storage::disk('public')->exists($product->image)) {
                $stats['failed']++;
                $bar->advance();
                continue;
            }

            if (!$force) {
                $webpVersions = ImageCompressionService::getExistingResponsiveWebP($product->image);
                if (!empty($webpVersions)) {
                    $stats['skipped']++;
                    $bar->advance();
                    continue;
                }
            }

            try {
                ImageCompressionService::processUploadedImage($product->image, [
                    'quality' => 85,
                    'webp_quality' => 85
                ]);
                $stats['processed']++;
            } catch (\Exception $e) {
                $stats['failed']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $stats;
    }

    protected function processNews(bool $force): array
    {
        $stats = ['processed' => 0, 'skipped' => 0, 'failed' => 0];
        
        $news = News::whereNotNull('featured_image')->get();
        $bar = $this->output->createProgressBar($news->count());
        $bar->start();

        foreach ($news as $item) {
            if (!$item->featured_image) {
                $stats['skipped']++;
                $bar->advance();
                continue;
            }

            if (!Storage::disk('public')->exists($item->featured_image)) {
                $stats['failed']++;
                $bar->advance();
                continue;
            }

            if (!$force) {
                $webpVersions = ImageCompressionService::getExistingResponsiveWebP($item->featured_image);
                if (!empty($webpVersions)) {
                    $stats['skipped']++;
                    $bar->advance();
                    continue;
                }
            }

            try {
                ImageCompressionService::processUploadedImage($item->featured_image, [
                    'quality' => 85,
                    'webp_quality' => 85
                ]);
                $stats['processed']++;
            } catch (\Exception $e) {
                $stats['failed']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $stats;
    }
}
