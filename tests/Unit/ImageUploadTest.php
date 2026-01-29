<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Traits\HandlesImageUpload;
use Intervention\Image\ImageManager;

class ImageUploadTest extends TestCase
{
    use HandlesImageUpload;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_can_upload_and_optimize_image()
    {
        // Create a dummy image
        $file = UploadedFile::fake()->image('test_image.jpg', 2000, 2000); // Larger than 1920x1080 to trigger resize

        // Call the trait method
        $path = $this->storeOptimizedImage($file, 'uploads/test');

        // Assert file exists
        Storage::disk('public')->assertExists($path);

        // Assert file is optimized/resized
        // We can't easily check compression ratio in mock, but we can check if Intervention was called if we mock it,
        // or just rely on the fact that code didn't crash and produced a file.
        // To be sure, we can check the file size or dimensions if we inspect the stored file.

        $fullPath = Storage::disk('public')->path($path);

        // Since Storage::fake stores files in a temp directory, we can check it.
        // However, storeOptimizedImage uses Storage::put which writes to the fake disk.
        // Intervention Image reads from the UploadedFile (which is a temp file).

        // Let's verify dimensions of the saved file
        // Note: Storage::fake keeps files in memory or temp, accessing via path() might fail if not configured.
        // But let's try reading it back.

        $content = Storage::disk('public')->get($path);
        $this->assertNotEmpty($content);

        // We can try to read dimensions from the content string
        $imageInfo = getimagesizefromstring($content);
        $this->assertNotNull($imageInfo);

        // Width should be resized to max 1920
        $this->assertLessThanOrEqual(1920, $imageInfo[0]);
        $this->assertLessThanOrEqual(1080, $imageInfo[1]);
    }

    public function test_it_handles_non_image_files_gracefully()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $path = $this->storeOptimizedImage($file, 'uploads/docs');

        Storage::disk('public')->assertExists($path);
        $this->assertStringEndsWith('.pdf', $path);
    }
}
