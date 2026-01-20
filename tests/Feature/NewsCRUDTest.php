<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\NewsImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsCRUDTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function admin_can_view_news_index()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.news.index');
    }

    #[Test]
    public function admin_can_view_create_news_form()
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.news.form-redesign');
    }

    #[Test]
    public function admin_can_create_news_with_valid_data()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Berita Test Baru',
            'content' => 'Ini adalah konten berita test yang cukup panjang untuk memenuhi validasi.',
            'excerpt' => 'Ringkasan berita test',
            'category' => 'Berita',
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success', 'Berita berhasil ditambahkan.');

        $this->assertDatabaseHas('news', [
            'title' => 'Berita Test Baru',
            'category' => 'Berita',
            'is_published' => true,
            'author_id' => $this->admin->id,
        ]);
    }

    #[Test]
    public function admin_can_create_news_with_slide_images()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Berita Dengan Slide',
            'content' => 'Konten berita dengan slide images.',
            'category' => 'Artikel',
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'slide_images' => [
                UploadedFile::fake()->image('slide1.jpg'),
                UploadedFile::fake()->image('slide2.jpg'),
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news = News::where('title', 'Berita Dengan Slide')->first();
        $this->assertNotNull($news);
        $this->assertCount(2, $news->images);
    }

    #[Test]
    public function admin_can_view_edit_news_form()
    {
        $news = News::factory()->create();

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.edit', $news));

        $response->assertStatus(200);
        $response->assertViewIs('admin.news.form-redesign');
        $response->assertViewHas('news');
    }

    #[Test]
    public function admin_can_update_news()
    {
        Storage::fake('public');

        $news = News::factory()->create([
            'title' => 'Judul Lama',
            'content' => 'Konten lama',
            'category' => 'Berita',
        ]);

        $data = [
            'title' => 'Judul Baru Diperbarui',
            'content' => 'Konten baru yang sudah diperbarui.',
            'category' => 'Artikel',
            'is_published' => false,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success', 'Berita berhasil diperbarui.');

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Judul Baru Diperbarui',
            'category' => 'Artikel',
            'is_published' => false,
        ]);
    }

    #[Test]
    public function admin_can_update_news_with_new_featured_image()
    {
        Storage::fake('public');

        $news = News::factory()->create([
            'featured_image' => 'news/old-image.jpg',
        ]);
        Storage::disk('public')->put('news/old-image.jpg', 'old-content');

        $data = [
            'title' => $news->title,
            'content' => $news->content,
            'category' => $news->category,
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'featured_image' => UploadedFile::fake()->image('new-image.jpg'),
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news->refresh();
        $this->assertNotEquals('news/old-image.jpg', $news->featured_image);
    }

    #[Test]
    public function admin_can_add_slide_images_to_existing_news()
    {
        Storage::fake('public');

        $news = News::factory()->create();

        $data = [
            'title' => $news->title,
            'content' => $news->content,
            'category' => $news->category,
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'slide_images' => [
                UploadedFile::fake()->image('new-slide.jpg'),
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news->refresh();
        $this->assertCount(1, $news->images);
    }

    #[Test]
    public function admin_cannot_add_more_than_7_slide_images()
    {
        Storage::fake('public');

        $news = News::factory()->create();

        // Add 6 existing images
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img1.jpg', 'order' => 1]);
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img2.jpg', 'order' => 2]);
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img3.jpg', 'order' => 3]);
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img4.jpg', 'order' => 4]);
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img5.jpg', 'order' => 5]);
        NewsImage::create(['news_id' => $news->id, 'image_path' => 'news/slides/img6.jpg', 'order' => 6]);

        $data = [
            'title' => $news->title,
            'content' => $news->content,
            'category' => $news->category,
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'slide_images' => [
                UploadedFile::fake()->image('slide1.jpg'),
                UploadedFile::fake()->image('slide2.jpg'),
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->put(route('admin.news.update', $news), $data);

        $response->assertSessionHas('error');
    }

    #[Test]
    public function admin_can_delete_news()
    {
        Storage::fake('public');

        $news = News::factory()->create([
            'featured_image' => 'news/test-image.jpg',
        ]);
        Storage::disk('public')->put('news/test-image.jpg', 'fake-content');

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success', 'Berita berhasil dihapus.');

        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    #[Test]
    public function admin_can_delete_slide_image()
    {
        Storage::fake('public');

        $news = News::factory()->create();
        $image = NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/slides/test.jpg',
            'order' => 1,
        ]);
        Storage::disk('public')->put('news/slides/test.jpg', 'fake-content');

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->delete(route('admin.news.delete-image', $image));

        $response->assertSessionHas('success', 'Foto slide berhasil dihapus.');
        $this->assertDatabaseMissing('news_images', ['id' => $image->id]);
    }

    #[Test]
    public function title_is_required_when_creating_news()
    {
        $data = [
            'title' => '',
            'content' => 'Konten test',
            'category' => 'Berita',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function content_is_required_when_creating_news()
    {
        $data = [
            'title' => 'Judul Test',
            'content' => '',
            'category' => 'Berita',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors('content');
    }

    #[Test]
    public function category_is_required_when_creating_news()
    {
        $data = [
            'title' => 'Judul Test',
            'content' => 'Konten test',
            'category' => '',
        ];

        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors('category');
    }

    #[Test]
    public function news_filters_work_correctly()
    {
        News::factory()->create(['title' => 'Berita Koperasi', 'category' => 'Berita', 'is_published' => true]);
        News::factory()->create(['title' => 'Artikel Investasi', 'category' => 'Artikel', 'is_published' => true]);
        News::factory()->create(['title' => 'Draft Pengumuman', 'category' => 'Pengumuman', 'is_published' => false]);

        // Test search filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.index', ['search' => 'Koperasi']));
        $response->assertStatus(200);
        $response->assertSee('Berita Koperasi');
        $response->assertDontSee('Artikel Investasi');

        // Test category filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.index', ['category' => 'Artikel']));
        $response->assertStatus(200);
        $response->assertSee('Artikel Investasi');
        $response->assertDontSee('Berita Koperasi');

        // Test status filter
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->get(route('admin.news.index', ['status' => 'draft']));
        $response->assertStatus(200);
        $response->assertSee('Draft Pengumuman');
    }

    #[Test]
    public function author_id_is_set_correctly_on_create()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Berita Author Test',
            'content' => 'Konten untuk test author.',
            'category' => 'Berita',
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $news = News::where('title', 'Berita Author Test')->first();
        $this->assertNotNull($news);
        $this->assertEquals($this->admin->id, $news->author_id);
        $this->assertEquals($this->admin->name, $news->author);
    }

    #[Test]
    public function slug_is_generated_automatically()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Judul Berita Untuk Slug Test',
            'content' => 'Konten berita.',
            'category' => 'Berita',
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
        ];

        $this->actingAs($this->admin)
            ->withoutMiddleware([
                \App\Http\Middleware\BlockSuspiciousRequests::class,
                \App\Http\Middleware\CheckMaintenanceMode::class,
                \App\Http\Middleware\LogVisitor::class,
                \App\Http\Middleware\OptimizeResponse::class,
            ])
            ->post(route('admin.news.store'), $data);

        $news = News::where('title', 'Judul Berita Untuk Slug Test')->first();
        $this->assertNotNull($news);
        $this->assertEquals('judul-berita-untuk-slug-test', $news->slug);
    }
}
