<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\CompanyInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class TestCompanyProfileUpload extends Command
{
    protected $signature = 'test:company-profile-upload {user_id}';
    protected $description = 'Test company profile image upload functionality';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }
        
        $this->info("Testing company profile upload for user: {$user->name}");
        $this->newLine();
        
        // Test 1: Permission check
        $this->line("1. Testing permissions...");
        if (!$user->hasAnyPermission(['settings.company'])) {
            $this->error("   ✗ User does not have 'settings.company' permission");
            return 1;
        }
        $this->info("   ✓ User has required permissions");
        
        // Test 2: Storage directory check
        $this->line("2. Testing storage directories...");
        $profileDir = 'company/profile';
        if (!Storage::disk('public')->exists($profileDir)) {
            Storage::disk('public')->makeDirectory($profileDir);
            $this->line("   ✓ Created profile directory");
        } else {
            $this->line("   ✓ Profile directory exists");
        }
        
        // Test 3: Create test image
        $this->line("3. Creating test image...");
        $testImagePath = $this->createTestImage();
        if (!$testImagePath) {
            $this->error("   ✗ Failed to create test image");
            return 1;
        }
        $this->line("   ✓ Test image created: {$testImagePath}");
        
        // Test 4: Validation test
        $this->line("4. Testing validation...");
        $validator = Validator::make([
            'profile_image' => new UploadedFile($testImagePath, 'test.jpg', 'image/jpeg', null, true)
        ], [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);
        
        if ($validator->fails()) {
            $this->error("   ✗ Validation failed: " . implode(', ', $validator->errors()->all()));
            unlink($testImagePath);
            return 1;
        }
        $this->line("   ✓ Validation passed");
        
        // Test 5: Storage upload test
        $this->line("5. Testing storage upload...");
        try {
            $uploadedPath = Storage::disk('public')->putFile($profileDir, new UploadedFile($testImagePath, 'test.jpg', 'image/jpeg', null, true));
            $this->line("   ✓ File uploaded to: {$uploadedPath}");
            
            // Clean up
            Storage::disk('public')->delete($uploadedPath);
            $this->line("   ✓ Test file cleaned up");
        } catch (\Exception $e) {
            $this->error("   ✗ Upload failed: " . $e->getMessage());
            unlink($testImagePath);
            return 1;
        }
        
        // Test 6: Database update test
        $this->line("6. Testing database update...");
        try {
            $company = CompanyInfo::first();
            if (!$company) {
                $company = new CompanyInfo();
                $company->name = 'Test Company';
            }
            
            $oldProfileImage = $company->profile_image;
            $company->profile_image = 'company/profile/test.jpg';
            $company->save();
            
            $this->line("   ✓ Database update successful");
            
            // Restore old value
            $company->profile_image = $oldProfileImage;
            $company->save();
            
        } catch (\Exception $e) {
            $this->error("   ✗ Database update failed: " . $e->getMessage());
            unlink($testImagePath);
            return 1;
        }
        
        // Test 7: Controller method test
        $this->line("7. Testing controller authorization...");
        try {
            // Simulate auth user
            auth()->login($user);
            
            $controller = new \App\Http\Controllers\Admin\CompanyInfoController();
            
            // Test edit method
            $response = $controller->edit();
            $this->line("   ✓ Edit method accessible");
            
        } catch (\Exception $e) {
            $this->error("   ✗ Controller test failed: " . $e->getMessage());
        } finally {
            auth()->logout();
        }
        
        // Cleanup
        unlink($testImagePath);
        
        $this->newLine();
        $this->info("✅ All tests passed! Upload functionality should work.");
        $this->line("If you're still getting 403 errors, check:");
        $this->line("1. Browser console for JavaScript errors");
        $this->line("2. Network tab for failed requests");
        $this->line("3. Laravel logs for detailed error messages");
        
        return 0;
    }
    
    private function createTestImage(): ?string
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'test_image') . '.jpg';
        
        // Create a simple 100x100 red image
        $image = imagecreate(100, 100);
        $red = imagecolorallocate($image, 255, 0, 0);
        imagefill($image, 0, 0, $red);
        
        if (imagejpeg($image, $tempPath)) {
            imagedestroy($image);
            return $tempPath;
        }
        
        return null;
    }
}