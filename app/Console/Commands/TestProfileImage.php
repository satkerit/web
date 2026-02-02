<?php

namespace App\Console\Commands;

use App\Models\CompanyInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class TestProfileImage extends Command
{
    protected $signature = 'test:profile-image';
    protected $description = 'Test profile_image column in company_infos table';

    public function handle()
    {
        $this->info('Testing profile_image column...');
        
        // 1. Check if column exists in database
        $hasColumn = Schema::hasColumn('company_infos', 'profile_image');
        $this->line("Column exists in database: " . ($hasColumn ? 'YES' : 'NO'));
        
        if (!$hasColumn) {
            $this->error('profile_image column does not exist in database!');
            $this->line('Run: php artisan migrate');
            return 1;
        }
        
        // 2. Check if we can create/retrieve CompanyInfo
        try {
            $company = CompanyInfo::first();
            if (!$company) {
                $this->line('No company record found, creating one...');
                $company = CompanyInfo::create([
                    'name' => 'Test Company',
                    'profile_image' => null
                ]);
                $this->line('Company record created successfully');
            } else {
                $this->line("Company found: {$company->name}");
            }
            
            // 3. Test accessing profile_image attribute
            $profileImage = $company->profile_image;
            $this->line("Profile image value: " . ($profileImage ?? 'NULL'));
            
            // 4. Test setting profile_image
            $company->profile_image = 'test/image.jpg';
            $company->save();
            $this->line('Successfully set and saved profile_image');
            
            // 5. Test retrieving again
            $company->refresh();
            $this->line("Profile image after save: " . ($company->profile_image ?? 'NULL'));
            
            $this->info('✅ profile_image column is working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error testing profile_image: ' . $e->getMessage());
            $this->line('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}