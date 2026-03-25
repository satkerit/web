<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MonitorImageUploads extends Command
{
    protected $signature = 'images:monitor {--cleanup : Clean up failed uploads}';
    protected $description = 'Monitor image upload performance and cleanup failed uploads';

    public function handle()
    {
        $this->info('Monitoring image uploads...');

        // Check storage disk space
        $this->checkStorageSpace();

        // Check for orphaned files
        $this->checkOrphanedFiles();

        // Cleanup if requested
        if ($this->option('cleanup')) {
            $this->cleanupFailedUploads();
        }

        // Show recent upload statistics
        $this->showUploadStats();

        $this->info('Image upload monitoring completed.');
    }

    protected function checkStorageSpace()
    {
        $disk = Storage::disk('public');
        $path = $disk->path('');
        
        if (function_exists('disk_free_space')) {
            $freeBytes = disk_free_space($path);
            $totalBytes = disk_total_space($path);
            
            if ($freeBytes && $totalBytes) {
                $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
                $totalGB = round($totalBytes / 1024 / 1024 / 1024, 2);
                $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 1);
                
                $this->info("Storage: {$freeGB}GB free of {$totalGB}GB total ({$usedPercent}% used)");
                
                if ($usedPercent > 90) {
                    $this->warn("Warning: Storage is {$usedPercent}% full!");
                    Log::warning("Storage space low: {$usedPercent}% used");
                }
            }
        }
    }

    protected function checkOrphanedFiles()
    {
        $newsPath = 'news';
        $galleryPath = 'news/gallery';
        
        $this->info('Checking for orphaned files...');
        
        // This would require database queries to check for files not referenced in DB
        // For now, just show file counts
        $newsFiles = collect(Storage::disk('public')->files($newsPath))->count();
        $galleryFiles = collect(Storage::disk('public')->files($galleryPath))->count();
        
        $this->info("News files: {$newsFiles}");
        $this->info("Gallery files: {$galleryFiles}");
    }

    protected function cleanupFailedUploads()
    {
        $this->info('Cleaning up temporary files...');
        
        // Clean up temporary files older than 1 hour
        $tempPath = storage_path('app/temp');
        if (is_dir($tempPath)) {
            $files = glob($tempPath . '/*');
            $cleaned = 0;
            
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file)) > 3600) {
                    unlink($file);
                    $cleaned++;
                }
            }
            
            $this->info("Cleaned up {$cleaned} temporary files");
        }
    }

    protected function showUploadStats()
    {
        $this->info('Recent upload statistics:');
        
        // Show PHP configuration
        $this->table(['Setting', 'Value'], [
            ['upload_max_filesize', ini_get('upload_max_filesize')],
            ['post_max_size', ini_get('post_max_size')],
            ['max_file_uploads', ini_get('max_file_uploads')],
            ['memory_limit', ini_get('memory_limit')],
            ['max_execution_time', ini_get('max_execution_time')],
        ]);
    }
}