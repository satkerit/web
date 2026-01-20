<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateStorageUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-urls 
                            {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Storage::url() to @storageUrl() in blade templates';

    protected int $filesProcessed = 0;
    protected int $replacementsMade = 0;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("🔄 Migrating Storage URLs to use @storageUrl directive");
        $this->newLine();
        
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn("⚠️  DRY RUN MODE - No files will be modified");
            $this->newLine();
        }
        
        // Find all blade files
        $bladeFiles = $this->findBladeFiles();
        
        $this->info("Found " . count($bladeFiles) . " blade files");
        $this->newLine();
        
        $bar = $this->output->createProgressBar(count($bladeFiles));
        $bar->start();
        
        foreach ($bladeFiles as $file) {
            $this->processFile($file, $dryRun);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Show summary
        $this->info("✅ Migration completed!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Files Processed', $this->filesProcessed],
                ['Replacements Made', $this->replacementsMade],
            ]
        );
        
        if ($dryRun) {
            $this->newLine();
            $this->info("💡 Run without --dry-run to apply changes");
        }
        
        return self::SUCCESS;
    }
    
    /**
     * Find all blade files
     */
    protected function findBladeFiles(): array
    {
        $files = [];
        
        $directories = [
            resource_path('views'),
        ];
        
        foreach ($directories as $directory) {
            if (File::isDirectory($directory)) {
                $found = File::allFiles($directory);
                foreach ($found as $file) {
                    if ($file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                        $files[] = $file->getPathname();
                    }
                }
            }
        }
        
        return $files;
    }
    
    /**
     * Process a single file
     */
    protected function processFile(string $filePath, bool $dryRun): void
    {
        $content = File::get($filePath);
        $originalContent = $content;
        
        // Pattern 1: {{ Storage::url($variable) }}
        $pattern1 = '/\{\{\s*Storage::url\(([^)]+)\)\s*\}\}/';
        $replacement1 = '{{ \App\Helpers\StorageHelper::url($1) }}';
        $content = preg_replace($pattern1, $replacement1, $content);
        
        // Pattern 2: {!! Storage::url($variable) !!}
        $pattern2 = '/\{!!\s*Storage::url\(([^)]+)\)\s*!!\}/';
        $replacement2 = '{!! \App\Helpers\StorageHelper::url($1) !!}';
        $content = preg_replace($pattern2, $replacement2, $content);
        
        // Pattern 3: @if with Storage::url
        $pattern3 = '/@if\s*\(\s*Storage::url\(([^)]+)\)\s*\)/';
        $replacement3 = '@if(\App\Helpers\StorageHelper::url($1))';
        $content = preg_replace($pattern3, $replacement3, $content);
        
        // Count replacements in this file
        if ($content !== $originalContent) {
            $this->filesProcessed++;
            
            // Count number of replacements
            $count = substr_count($content, 'StorageHelper::url') - substr_count($originalContent, 'StorageHelper::url');
            $this->replacementsMade += $count;
            
            if (!$dryRun) {
                File::put($filePath, $content);
            }
        }
    }
}
