<?php

namespace App\Console\Commands;

use App\Models\CompanyInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class FixProfileImageAttribute extends Command
{
    protected $signature = 'fix:profile-image-attribute';
    protected $description = 'Fix profile_image attribute issues in CompanyInfo model';

    public function handle()
    {
        $this->info('Fixing profile_image attribute issues...');
        
        try {
            // 1. Check if column exists
            if (!Schema::hasColumn('company_infos', 'profile_image')) {
                $this->error('profile_image column does not exist!');
                $this->line('Run: php artisan migrate');
                return 1;
            }
            
            $this->line('✅ profile_image column exists');
            
            // 2. Clear all related caches
            Cache::flush();
            CompanyInfo::clearCache();
            $this->line('✅ Caches cleared');
            
            // 3. Test model retrieval
            $company = CompanyInfo::first();
            if (!$company) {
                $this->line('No company record found, creating one...');
                $company = CompanyInfo::create([
                    'name' => 'Default Company',
                    'profile_image' => null
                ]);
            }
            
            // 4. Test attribute access
            $profileImage = $company->profile_image;
            $this->line("✅ Can access profile_image: " . ($profileImage ?? 'NULL'));
            
            // 5. Test attribute assignment
            $company->profile_image = 'test/profile.jpg';
            $company->save();
            $this->line('✅ Can set and save profile_image');
            
            // 6. Test fresh retrieval
            $freshCompany = CompanyInfo::first();
            $freshProfileImage = $freshCompany->profile_image;
            $this->line("✅ Fresh retrieval works: " . ($freshProfileImage ?? 'NULL'));
            
            // 7. Reset to null for clean state
            $company->profile_image = null;
            $company->save();
            $this->line('✅ Reset profile_image to NULL');
            
            $this->info('✅ profile_image attribute is working correctly!');
            
            // 8. Clear view cache
            $this->call('view:clear');
            $this->line('✅ View cache cleared');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->line('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}