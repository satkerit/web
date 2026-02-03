<?php

namespace Tests\Feature\Admin;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HeroSlideCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createSuperAdmin();
    }

    #[Test]
    public function admin_can_view_hero_slides_index()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.hero-slides.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hero-slides.index');
    }

    #[Test]
    public function admin_can_create_hero_slide()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Test Hero Slide',
            'subtitle' => 'Test subtitle for hero slide',
            'image' => UploadedFile::fake()->image('hero.jpg', 1920, 1080),
            'link_url' => 'https://example.com',
            'link_text' => 'Learn More',
            'is_active' => true,
            'order_position' => 1,
            'transition_type' => 'slide',
            'transition_duration' => 5000,
            'show_title' => true,
            'show_subtitle' => true,
            'show_button' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.hero-slides.store'), $data);

        $response->assertRedirect(route('admin.hero-slides.index'));
        $response->assertSessionHas('success', 'Slide berhasil ditambahkan.');

        $this->assertDatabaseHas('hero_slides', [
            'title' => 'Test Hero Slide',
            'subtitle' => 'Test subtitle for hero slide',
        ]);
    }

    #[Test]
    public function admin_can_update_hero_slide()
    {
        Storage::fake('public');

        $heroSlide = HeroSlide::factory()->create([
            'title' => 'Old Title',
            'image' => 'hero-slides/old-image.jpg',
        ]);

        $data = [
            'title' => 'Updated Hero Slide Title',
            'subtitle' => 'Updated subtitle',
            'is_active' => true,
            'order_position' => 2,
            'transition_type' => 'fade',
            'transition_duration' => 4000,
            'show_title' => true,
            'show_subtitle' => true,
            'show_button' => false,
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.hero-slides.update', $heroSlide), $data);

        $response->assertRedirect(route('admin.hero-slides.index'));
        $response->assertSessionHas('success', 'Slide berhasil diperbarui.');

        $this->assertDatabaseHas('hero_slides', [
            'id' => $heroSlide->id,
            'title' => 'Updated Hero Slide Title',
            'subtitle' => 'Updated subtitle',
        ]);
    }

    #[Test]
    public function admin_can_delete_hero_slide()
    {
        Storage::fake('public');

        $heroSlide = HeroSlide::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->delete(route('admin.hero-slides.destroy', $heroSlide));

        $response->assertRedirect(route('admin.hero-slides.index'));
        $response->assertSessionHas('success', 'Slide berhasil dihapus.');

        $this->assertDatabaseMissing('hero_slides', ['id' => $heroSlide->id]);
    }

    #[Test]
    public function admin_can_reorder_hero_slides()
    {
        $slide1 = HeroSlide::factory()->create(['order_position' => 0]);
        $slide2 = HeroSlide::factory()->create(['order_position' => 1]);
        $slide3 = HeroSlide::factory()->create(['order_position' => 2]);

        // Reorder: slide3 first, slide1 second, slide2 third
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.hero-slides.reorder'), [
                'order' => [$slide3->id, $slide1->id, $slide2->id],
            ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('hero_slides', ['id' => $slide3->id, 'order_position' => 0]);
        $this->assertDatabaseHas('hero_slides', ['id' => $slide1->id, 'order_position' => 1]);
        $this->assertDatabaseHas('hero_slides', ['id' => $slide2->id, 'order_position' => 2]);
    }
}
