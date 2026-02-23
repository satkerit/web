<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixHeroSlideColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:hero-slide-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix hero_slide_limit column in site_settings table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking hero_slide_limit column...');

        try {
            // Check if column exists
            $hasColumn = Schema::hasColumn('site_settings', 'hero_slide_limit');

            if ($hasColumn) {
                $this->info('✅ hero_slide_limit column already exists');
                
                // Show current value
                $current = DB::table('site_settings')->first();
                if ($current) {
                    $this->line('Current hero_slide_limit: ' . ($current->hero_slide_limit ?? 'not set'));
                }
                
                return Command::SUCCESS;
            }

            $this->warn('❌ hero_slide_limit column not found. Creating it...');

            // Add the column
            Schema::table('site_settings', function ($table) {
                $table->integer('hero_slide_limit')->default(5)->after('hero_slider_delay')->comment('Maksimal jumlah slide hero yang ditampilkan');
            });

            // Update existing records
            DB::table('site_settings')->update(['hero_slide_limit' => 5]);

            $this->info('✅ hero_slide_limit column created successfully');
            
            // Verify
            $updated = DB::table('site_settings')->first();
            if ($updated) {
                $this->line('Default value set to: ' . $updated->hero_slide_limit);
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to fix hero_slide_limit column: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}