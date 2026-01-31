<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\CompanyInfo;
use App\Models\BlockedIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TroubleshootCompanyUpload extends Command
{
    protected $signature = 'troubleshoot:company-upload {user_id?}';
    protected $description = 'Comprehensive troubleshooting for company profile upload 403 errors';

    public function handle()
    {
        $this->info("=== Company Profile Upload Troubleshooting ===\n");
        
        // 1. Check system requirements
        $this->checkSystemRequirements();
        
        // 2. Check storage configuration
        $this->checkStorageConfiguration();
        
        // 3. Check user permissions
        $userId = $this->argument('user_id');
        if ($userId) {
            $this->checkUserPermissions($userId);
        } else {
            $this->info("Skipping user permission check (no user ID provided)");
        }
        
        // 4. Check middleware and security
        $this->checkSecurityMiddleware();
        
        // 5. Test storage API endpoint
        $this->testStorageApiEndpoint();
        
        // 6. Check company info model
        $this->checkCompanyInfoModel();
        
        // 7. Provide recommendations
        $this->provideRecommendations();
        
        return 0;
    }
    
    protected function checkSystemRequirements()
    {
        $this->info("1. System Requirements Check");
        $this->line("   PHP Version: " . PHP_VERSION);
        $this->line("   Laravel Version: " . app()->version());
        
        // Check required extensions
        $extensions = ['gd', 'imagick', 'fileinfo'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            $this->line("   Extension {$ext}: " . ($loaded ? '✅ Loaded' : '❌ Missing'));
        }
        
        // Check memory limit
        $memoryLimit = ini_get('memory_limit');
        $this->line("   Memory Limit: {$memoryLimit}");
        
        // Check upload limits
        $maxFilesize = ini_get('upload_max_filesize');
        $maxPostSize = ini_get('post_max_size');
        $this->line("   Max File Size: {$maxFilesize}");
        $this->line("   Max Post Size: {$maxPostSize}");
        
        $this->line("");
    }
    
    protected function checkStorageConfiguration()
    {
        $this->info("2. Storage Configuration Check");
        
        // Check storage disk
        try {
            $disk = Storage::disk('public');
            $this->line("   ✅ Public disk accessible");
            
            // Check if storage link exists
            $linkPath = public_path('storage');
            if (is_link($linkPath)) {
                $this->line("   ✅ Storage symlink exists");
                $target = readlink($linkPath);
                $this->line("   Link target: {$target}");
            } else {
                $this->error("   ❌ Storage symlink missing");
                $this->line("   Run: php artisan storage:link");
            }
            
            // Check storage permissions
            $storagePath = storage_path('app/public');
            if (is_writable($storagePath)) {
                $this->line("   ✅ Storage directory writable");
            } else {
                $this->error("   ❌ Storage directory not writable");
                $this->line("   Path: {$storagePath}");
            }
            
            // Check company directory
            $companyPath = 'company/profile';
            if ($disk->exists($companyPath)) {
                $this->line("   ✅ Company profile directory exists");
            } else {
                $this->line("   ⚠️  Company profile directory missing (will be created on upload)");
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Storage error: " . $e->getMessage());
        }
        
        $this->line("");
    }
    
    protected function checkUserPermissions($userId)
    {
        $this->info("3. User Permissions Check");
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("   ❌ User not found: {$userId}");
            return;
        }
        
        $this->line("   User: {$user->name} (ID: {$user->id})");
        $this->line("   Email: {$user->email}");
        $this->line("   Role ID: " . ($user->role_id ?? 'NULL'));
        
        if ($user->role_id && $user->role) {
            $this->line("   Role Name: {$user->role->name}");
        } else {
            $this->error("   ❌ User has no role assigned");
        }
        
        // Check key permissions
        $permissions = [
            'settings.company' => 'Company settings access',
            'storage.view' => 'Storage view access'
        ];
        
        foreach ($permissions as $perm => $desc) {
            try {
                $has = $user->hasAnyPermission([$perm]);
                $this->line("   {$desc}: " . ($has ? '✅ YES' : '❌ NO'));
            } catch (\Exception $e) {
                $this->error("   {$desc}: ERROR - " . $e->getMessage());
            }
        }
        
        // Check role methods
        try {
            $isAdmin = $user->isAdmin();
            $isEditor = $user->isEditor();
            $this->line("   Is Admin: " . ($isAdmin ? '✅ YES' : '❌ NO'));
            $this->line("   Is Editor: " . ($isEditor ? '✅ YES' : '❌ NO'));
        } catch (\Exception $e) {
            $this->error("   Role method error: " . $e->getMessage());
        }
        
        $this->line("");
    }
    
    protected function checkSecurityMiddleware()
    {
        $this->info("4. Security Middleware Check");
        
        // Check if IP is blocked
        try {
            $currentIp = request()->ip() ?? '127.0.0.1';
            $isBlocked = BlockedIp::isBlocked($currentIp);
            $this->line("   Current IP: {$currentIp}");
            $this->line("   IP Blocked: " . ($isBlocked ? '❌ YES' : '✅ NO'));
            
            // Check rate limiting
            $rateLimitKey = "admin_minute:{$currentIp}:1";
            $remaining = \Illuminate\Support\Facades\RateLimiter::remaining($rateLimitKey, 60);
            $this->line("   Rate Limit Remaining: {$remaining}/60");
            
        } catch (\Exception $e) {
            $this->error("   Security check error: " . $e->getMessage());
        }
        
        $this->line("");
    }
    
    protected function testStorageApiEndpoint()
    {
        $this->info("5. Storage API Endpoint Test");
        
        try {
            // Test if route exists
            $route = route('admin.storage.api.browse');
            $this->line("   API Route: {$route}");
            
            // Note: We can't actually test the HTTP request here without authentication
            $this->line("   ⚠️  Manual test required - check browser network tab");
            
        } catch (\Exception $e) {
            $this->error("   ❌ Route error: " . $e->getMessage());
        }
        
        $this->line("");
    }
    
    protected function checkCompanyInfoModel()
    {
        $this->info("6. Company Info Model Check");
        
        try {
            $company = CompanyInfo::first();
            if ($company) {
                $this->line("   ✅ Company info record exists");
                $this->line("   Company Name: " . ($company->name ?? 'Not set'));
                $this->line("   Profile Image: " . ($company->profile_image ?? 'Not set'));
            } else {
                $this->line("   ⚠️  No company info record (will be created on first save)");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Model error: " . $e->getMessage());
        }
        
        $this->line("");
    }
    
    protected function provideRecommendations()
    {
        $this->info("7. Recommendations");
        
        $this->line("   To fix 403 errors, try these steps:");
        $this->line("");
        $this->line("   1. Fix user permissions:");
        $this->line("      php artisan fix:company-profile-access {user_id}");
        $this->line("");
        $this->line("   2. Debug specific user:");
        $this->line("      php artisan debug:user-permissions {user_id}");
        $this->line("");
        $this->line("   3. Clear caches:");
        $this->line("      php artisan cache:clear");
        $this->line("      php artisan config:clear");
        $this->line("      php artisan route:clear");
        $this->line("");
        $this->line("   4. Check browser console for JavaScript errors");
        $this->line("   5. Check Laravel logs: storage/logs/laravel.log");
        $this->line("   6. Test with different browser/incognito mode");
        $this->line("");
        $this->line("   If issues persist, check the updated StorageController");
        $this->line("   which now has better logging and permission checks.");
    }
}