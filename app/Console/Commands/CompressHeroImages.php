<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Services\ImageCompressionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompressHeroImages extends Command
{
    protected $signature = 'images:compress-hero {--force : Force recompression of existing images}';
    protected $description = 'Compress all hero slider images for better performance';

    public function handle()
    {
        $this->info('Starting hero image compression...');
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

        $compressed = 0;
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

                // Compress main image
                $compressedPath = ImageCompressionService::compressForWeb($slide->image, 75, 1920);
                
                // Generate responsive sizes
                $responsiveImages = ImageCompressionService::generateResponsiveSizes($slide->image);

                $compressedFullPath = Storage::path($compressedPath);
                $compressedSize = file_exists($compressedFullPath) ? filesize($compressedFullPath) : 0;
                
                $saved = $originalSize - $compressedSize;
                $totalSaved += $saved;

                $compressed++;
                
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
        $this->info('Compression Summary:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Slides', $heroSlides->count()],
                ['Compressed', $compressed],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Space Saved', $this->formatBytes($totalSaved)],
                ['Average Savings', $compressed > 0 ? $this->formatBytes($totalSaved / $compressed) : '0 B'],
            ]
        );

        $this->newLine();
        $this->info('✓ Hero image compression completed!');
        
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
