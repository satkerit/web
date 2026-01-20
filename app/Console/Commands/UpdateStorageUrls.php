<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateStorageUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:update-urls 
                            {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update storage URLs in blade views to use StorageHelper';

    protected $filesUpdated = 0;
    protected $replacementsMade = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No files will be modified');
            $this->newLine();
        }
        
        $this->info('Scanning blade files for storage URL patterns...');
        $this->newLine();
        
        $viewsPath = resource_path('views');
        $bladeFiles = $this->getBladeFiles($viewsPath);
        
        $this->info("Found " . count($bladeFiles) . " blade files");
        $this->newLine();
        
        foreach ($bladeFiles as $file) {
            $this->processFile($file, $dryRun);
        }
        
        $this->newLine();
        $this->info('===========================================');
        $this->info("Files updated: {$this->filesUpdated}");
        $this->info("Replacements made: {$this->replacementsMade}");
        $this->info('===========================================');
        
        if ($dryRun) {
            $this->newLine();
            $this->info('Run without --dry-run to apply changes');
        } else {
            $this->newLine();
            $this->info('✓ All files updated successfully!');
            $this->warn('Remember to test your application after this update.');
        }
        
        return 0;
    }
    
    /**
     * Get all blade files recursively
     */
    protected function getBladeFiles($directory)
    {
        $files = [];
        
        foreach (File::allFiles($directory) as $file) {
            if ($file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }
    
    /**
     * Process a single file
     */
    protected function processFile($filePath, $dryRun)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        $fileChanged = false;
        $fileReplacements = 0;
        
        // Pattern 1: Storage::url($variable)
        $pattern1 = '/\{\{\s*Storage::url\(([^)]+)\)\s*\}\}/';
        $replacement1 = '{{ storage_url($1) }}';
        if (preg_match($pattern1, $content)) {
            $content = preg_replace($pattern1, $replacement1, $content);
            $count = preg_match_all($pattern1, $originalContent);
            $fileReplacements += $count;
            $fileChanged = true;
        }
        
        // Pattern 2: asset('storage/' . $variable)
        $pattern2 = '/\{\{\s*asset\([\'"]storage\/[\'"]\.?\s*\.\s*([^)]+)\)\s*\}\}/';
        $replacement2 = '{{ storage_url($1) }}';
        if (preg_match($pattern2, $content)) {
            $content = preg_replace($pattern2, $replacement2, $content);
            $count = preg_match_all($pattern2, $originalContent);
            $fileReplacements += $count;
            $fileChanged = true;
        }
        
        // Pattern 3: asset("storage/" . $variable)
        $pattern3 = '/\{\{\s*asset\("storage\/"\s*\.\s*([^)]+)\)\s*\}\}/';
        $replacement3 = '{{ storage_url($1) }}';
        if (preg_match($pattern3, $content)) {
            $content = preg_replace($pattern3, $replacement3, $content);
            $count = preg_match_all($pattern3, $originalContent);
            $fileReplacements += $count;
            $fileChanged = true;
        }
        
        // Pattern 4: url('storage/' . $variable)
        $pattern4 = '/\{\{\s*url\([\'"]storage\/[\'"]\.?\s*\.\s*([^)]+)\)\s*\}\}/';
        $replacement4 = '{{ storage_url($1) }}';
        if (preg_match($pattern4, $content)) {
            $content = preg_replace($pattern4, $replacement4, $content);
            $count = preg_match_all($pattern4, $originalContent);
            $fileReplacements += $count;
            $fileChanged = true;
        }
        
        if ($fileChanged) {
            $relativePath = str_replace(base_path() . '/', '', $filePath);
            
            if ($dryRun) {
                $this->line("Would update: {$relativePath} ({$fileReplacements} replacements)");
            } else {
                File::put($filePath, $content);
                $this->line("✓ Updated: {$relativePath} ({$fileReplacements} replacements)");
            }
            
            $this->filesUpdated++;
            $this->replacementsMade += $fileReplacements;
        }
    }
}
