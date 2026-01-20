<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanMissingReportFiles extends Command
{
    protected $signature = 'reports:clean-missing-files {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Clean up report records that reference missing files';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Checking for reports with missing files...');
        $this->newLine();

        $reports = Report::all();
        $missingCount = 0;
        $missingReports = [];

        foreach ($reports as $report) {
            if ($report->file_path && !Storage::disk('public')->exists($report->file_path)) {
                $missingCount++;
                $missingReports[] = $report;
                
                $this->warn("Missing file: {$report->file_path}");
                $this->line("  Report ID: {$report->id}");
                $this->line("  Title: {$report->title}");
                $this->line("  Type: {$report->type}");
                $this->line("  Year: {$report->year}");
                $this->newLine();
            }
        }

        if ($missingCount === 0) {
            $this->info('✓ All report files exist!');
            return 0;
        }

        $this->warn("Found {$missingCount} report(s) with missing files.");
        $this->newLine();

        if ($dryRun) {
            $this->info('Dry run mode - no changes made.');
            $this->info('Run without --dry-run to delete these records.');
            return 0;
        }

        if ($this->confirm('Do you want to delete these report records?', false)) {
            foreach ($missingReports as $report) {
                $report->delete();
                $this->info("✓ Deleted report: {$report->title}");
            }
            
            $this->newLine();
            $this->info("Successfully deleted {$missingCount} report record(s).");
        } else {
            $this->info('Operation cancelled.');
        }

        return 0;
    }
}
