<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InterventionImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_image_service_can_be_resolved()
    {
        $service = app(ImageService::class);
        $this->assertInstanceOf(ImageService::class, $service);
    }

    public function test_hero_slide_can_generate_responsive_urls()
    {
        $slide = HeroSlide::create([
            'title' => 'Test Slide',
            'image' => 'hero-slides/test-image.jpg',
            'is_active' => true,
            'order_position' => 1
        ]);

        // Test responsive URLs
        $urls = $slide->getResponsiveUrls();
        $this->assertIsArray($urls);

        // Test srcset generation
        $srcset = $slide->getSrcset();
        $this->assertIsString($srcset);

        // Test specific size URL
        $imageUrl = $slide->getImageUrl('large');
        $this->assertIsString($imageUrl);
    }

    public function test_image_service_generates_multiple_sizes()
    {
        $imageService = new ImageService();

        // Create a test image
        $file = UploadedFile::fake()->image('test.jpg', 1920, 1080);

        // Upload and process
        $result = $imageService->uploadHeroSliderImage($file);

        $this->assertArrayHasKey('original', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('large', $result['sizes']);
        $this->assertArrayHasKey('medium', $result['sizes']);
        $this->assertArrayHasKey('small', $result['sizes']);
        $this->assertArrayHasKey('mobile', $result['sizes']);
    }

    public function test_image_service_can_delete_all_variants()
    {
        $imageService = new ImageService();

        // Create a test image
        $file = UploadedFile::fake()->image('test.jpg', 1920, 1080);

        // Upload and process
        $result = $imageService->uploadHeroSliderImage($file);
        $imagePath = $result['original'];

        // Verify files exist
        Storage::disk('public')->assertExists($imagePath);

        // Delete all variants
        $imageService->deleteHeroSliderImage($imagePath);

        // Verify files are deleted
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_responsive_image_trait_methods()
    {
        $slide = HeroSlide::create([
            'title' => 'Test Slide',
            'image' => 'hero-slides/test-image.jpg',
            'is_active' => true,
            'order_position' => 1
        ]);

        // Test trait methods
        $this->assertIsArray($slide->getResponsiveUrls());
        $this->assertIsString($slide->getSrcset());
        $this->assertIsString($slide->getImageUrl());
        $this->assertIsArray($slide->getImageSizes());
    }
}
