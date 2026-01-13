<?php

namespace Tests\Integration;

use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->createAdmin();
    }

    #[Test]
    public function product_image_upload_stores_file_correctly(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        $data = [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            'description' => 'Test description',
            'image' => $image,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);

        // Verify file exists in storage
        Storage::disk('public')->assertExists($product->image);
    }

    #[Test]
    public function report_pdf_upload_stores_file_correctly(): void
    {
        Storage::fake('public');

        $pdf = UploadedFile::fake()->create('report.pdf', 1024, 'application/pdf');

        $data = [
            'title' => 'Test Report',
            'type' => 'keuangan_publikasi',
            'year' => date('Y'),
            'quarter' => 1,
            'file' => $pdf,
            'description' => 'Test report description',
            'is_published' => true,
            'posting_mode' => 'auto',
            'published_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.reports.store'), $data);

        $response->assertRedirect(route('admin.reports.index'));

        $report = Report::first();
        $this->assertNotNull($report);
        $this->assertNotNull($report->file_path);

        // Verify file exists in storage
        Storage::disk('public')->assertExists($report->file_path);
    }

    #[Test]
    public function hero_slide_image_upload_stores_file_correctly(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('hero.jpg', 1920, 1080);

        $data = [
            'title' => 'Test Hero Slide',
            'subtitle' => 'Test subtitle',
            'image' => $image,
            'is_active' => true,
            'order_position' => 1,
            'show_title' => true,
            'show_subtitle' => true,
            'show_button' => false,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.hero-slides.store'), $data);

        $response->assertRedirect(route('admin.hero-slides.index'));

        $heroSlide = HeroSlide::first();
        $this->assertNotNull($heroSlide);
        $this->assertNotNull($heroSlide->image);

        // Verify file exists in storage
        Storage::disk('public')->assertExists($heroSlide->image);
    }

    #[Test]
    public function invalid_file_type_is_rejected_for_product_image(): void
    {
        Storage::fake('public');

        // Try to upload a PDF as an image
        $invalidFile = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $data = [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            'description' => 'Test description',
            'image' => $invalidFile,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.products.store'), $data);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('products', 0);
    }

    #[Test]
    public function invalid_file_type_is_rejected_for_report(): void
    {
        Storage::fake('public');

        // Try to upload an image as a PDF
        $invalidFile = UploadedFile::fake()->image('image.jpg', 800, 600);

        $data = [
            'title' => 'Test Report',
            'type' => 'keuangan_publikasi',
            'year' => date('Y'),
            'quarter' => 1,
            'file' => $invalidFile,
            'description' => 'Test report description',
            'is_published' => true,
            'posting_mode' => 'auto',
            'published_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.reports.store'), $data);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('reports', 0);
    }

    #[Test]
    public function product_file_is_deleted_when_record_is_deleted(): void
    {
        Storage::fake('public');

        // First create a product with an image
        $image = UploadedFile::fake()->image('product.jpg', 800, 600);

        $data = [
            'name' => 'Test Product',
            'type' => 'simpanan_syariah',
            'description' => 'Test description',
            'image' => $image,
            'is_active' => true,
        ];

        $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.products.store'), $data);

        $product = Product::first();
        $imagePath = $product->image;

        // Verify file exists
        Storage::disk('public')->assertExists($imagePath);

        // Delete the product
        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));

        // Verify product is deleted
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        // Verify file is deleted
        Storage::disk('public')->assertMissing($imagePath);
    }

    #[Test]
    public function report_file_is_deleted_when_record_is_deleted(): void
    {
        Storage::fake('public');

        // First create a report with a file
        $pdf = UploadedFile::fake()->create('report.pdf', 1024, 'application/pdf');

        $data = [
            'title' => 'Test Report',
            'type' => 'keuangan_publikasi',
            'year' => date('Y'),
            'quarter' => 1,
            'file' => $pdf,
            'description' => 'Test report description',
            'is_published' => true,
            'posting_mode' => 'auto',
            'published_date' => now()->format('Y-m-d'),
        ];

        $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.reports.store'), $data);

        $report = Report::first();
        $filePath = $report->file_path;

        // Verify file exists
        Storage::disk('public')->assertExists($filePath);

        // Delete the report
        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->delete(route('admin.reports.destroy', $report));

        $response->assertRedirect(route('admin.reports.index'));

        // Verify report is deleted
        $this->assertDatabaseMissing('reports', ['id' => $report->id]);

        // Verify file is deleted
        Storage::disk('public')->assertMissing($filePath);
    }

    #[Test]
    public function hero_slide_file_is_deleted_when_record_is_deleted(): void
    {
        Storage::fake('public');

        // First create a hero slide with an image
        $image = UploadedFile::fake()->image('hero.jpg', 1920, 1080);

        $data = [
            'title' => 'Test Hero Slide',
            'subtitle' => 'Test subtitle',
            'image' => $image,
            'is_active' => true,
            'order_position' => 1,
            'show_title' => true,
            'show_subtitle' => true,
            'show_button' => false,
        ];

        $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.hero-slides.store'), $data);

        $heroSlide = HeroSlide::first();
        $imagePath = $heroSlide->image;

        // Verify file exists
        Storage::disk('public')->assertExists($imagePath);

        // Delete the hero slide
        $response = $this->actingAs($this->admin)
            ->withoutSecurityMiddleware()
            ->delete(route('admin.hero-slides.destroy', $heroSlide));

        $response->assertRedirect(route('admin.hero-slides.index'));

        // Verify hero slide is deleted
        $this->assertDatabaseMissing('hero_slides', ['id' => $heroSlide->id]);

        // Verify file is deleted
        Storage::disk('public')->assertMissing($imagePath);
    }
}
