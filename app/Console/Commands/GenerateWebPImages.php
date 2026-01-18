<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Services\WebPConverterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateWebPImages extends Command
{
    protected $signature = 'images:generate-webp {--force : Force regeneration of existing WebP images}';
    protected $description = 'Generate WebP versions of hero slider images for better compression';

    public function handle()
    {
        $this->info('Starting WebP image generation...');
        $this->newLine();

        $heroSlides = HeroSlide::where('is_active', true)
            ->whereNotNull('image')
            ->get();

        if ($heroSlides->isEmpty()) {
            $this->warn('No hero slides found with images.');
            return Command::SUCCESS;
        }

        $this->info("Found {$heroSlides->count()} hero slides to process.");
        $this->newLine();

        $bar = $this->output->createProgressBar($heroSlides->count());
        $bar->start();

        $generated = 0;
        $skipped = 0;
        $errors = 0;
        $totalSaved = 0;

        foreach ($heroSlides as $slide) {
            try {
                $originalPath = Storage::path($slide->image);
                
                if (!file_exists($originalPath)) {
                    $this->newLine();
                    $this->warn("Image not found: {$slide->image}");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $originalSize = filesize($originalPath);

                // Generate main WebP
                $webpPath = WebPConverterService::convertToWebP($slide->image, 75);
                
                // Generate responsive WebP sizes
                $responsiveWebP = WebPConverterService::generateResponsiveWebP($slide->image);

                if ($webpPath) {
                    $webpFullPath = Storage::path($webpPath);
                    $webpSize = file_exists($webpFullPath) ? filesize($webpFullPath) : 0;
                    
                    $saved = $originalSize - $webpSize;
                    $totalSaved += $saved;
                    $generated++;
                } else {
                    $errors++;
                }
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error processing {$slide->image}: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('WebP Generation Summary:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Slides', $heroSlides->count()],
                ['Generated', $generated],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Space Saved', $this->formatBytes($totalSaved)],
                ['Average Savings', $generated > 0 ? $this->formatBytes($totalSaved / $generated) : '0 B'],
                ['Compression Ratio', $generated > 0 ? round(($totalSaved / ($generated * 1024 * 1024)) * 100, 1) . '%' : '0%'],
            ]
        );

        $this->newLine();
        $this->info('✓ WebP image generation completed!');
        
        // Clear cache
        $this->call('cache:clear');
        $this->call('view:clear');

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