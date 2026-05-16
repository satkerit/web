<?php

namespace Tests\Feature\Admin;

use App\Models\News;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsFormIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Use helper from TestCase or find existing role
        $this->user = $this->createSuperAdmin();
    }

    #[Test]
    public function it_can_access_news_create_form()
    {
        $response = $this->actingAs($this->user)
            ->withoutMiddleware(\App\Http\Middleware\OptimizeResponse::class)
            ->get(route('admin.news.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.news.create');
        $response->assertSee('Tambah Berita Baru');
    }

    #[Test]
    public function it_can_create_news_with_all_fields()
    {
        $data = [
            'title' => 'Test Berita Baru',
            'slug' => 'test-berita-baru',
            'content' => '<p>Ini adalah konten berita test</p>',
            'excerpt' => 'Ringkasan berita test',
            'category' => 'Berita',
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('news', [
            'title' => 'Test Berita Baru',
            'slug' => 'test-berita-baru',
            'category' => 'Berita',
            'is_published' => true
        ]);
    }

    #[Test]
    public function it_can_access_news_edit_form()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->withoutMiddleware(\App\Http\Middleware\OptimizeResponse::class)
            ->get(route('admin.news.edit', $news));

        $response->assertStatus(200);
        $response->assertViewIs('admin.news.edit');
        $response->assertViewHas('news', $news);
        $response->assertSee('Edit Berita');
        $response->assertSee($news->title);
    }

    #[Test]
    public function it_can_update_existing_news()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id,
            'title' => 'Original Title',
            'slug' => 'original-title'
        ]);

        $data = [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'content' => '<p>Updated content</p>',
            'excerpt' => 'Updated excerpt',
            'category' => 'Pengumuman',
            'author' => 'Updated Author',
            'meta_description' => 'Updated meta',
            'tags' => 'updated, tags',
            'published_at' => now()->format('Y-m-d\TH:i'),
            'featured_image' => UploadedFile::fake()->image('updated.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'category' => 'Pengumuman'
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), []);

        $response->assertSessionHasErrors(['title', 'content', 'category']);
    }

    #[Test]
    public function it_validates_slug_uniqueness()
    {
        News::factory()->create([
            'slug' => 'existing-slug',
            'author_id' => $this->user->id
        ]);

        $data = [
            'title' => 'New Title',
            'slug' => 'existing-slug',
            'content' => '<p>Content</p>',
            'category' => 'Berita',
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors(['slug']);
    }

    #[Test]
    public function it_validates_image_upload()
    {
        $data = [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'content' => '<p>Content</p>',
            'category' => 'Berita',
            'is_published' => true,
            'featured_image' => UploadedFile::fake()->create('document.pdf', 1000)
        ];

        $response = $this->actingAs($this->user)
            ->withoutMiddleware(\App\Http\Middleware\BlockSuspiciousRequests::class)
            ->post(route('admin.news.store'), $data);

        $response->assertSessionHasErrors(['featured_image']);
    }

    #[Test]
    public function it_can_upload_gallery_images()
    {
        $data = [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'content' => '<p>Content</p>',
            'category' => 'Berita',
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true,
            'slide_images' => [
                UploadedFile::fake()->image('gallery1.jpg'),
                UploadedFile::fake()->image('gallery2.jpg'),
                UploadedFile::fake()->image('gallery3.jpg')
            ]
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news = News::where('slug', 'test-title')->first();
        $this->assertNotNull($news);
        $this->assertEquals(3, $news->images()->count());
    }

    #[Test]
    public function it_limits_gallery_images_to_seven()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id
        ]);

        // Create 7 existing images
        for ($i = 0; $i < 7; $i++) {
            $news->images()->create([
                'image_path' => "news/slides/image{$i}.jpg",
                'order' => $i
            ]);
        }

        $data = [
            'title' => $news->title,
            'slug' => $news->slug,
            'content' => $news->content,
            'category' => $news->category,
            'is_published' => true,
            'slide_images' => [
                UploadedFile::fake()->image('extra.jpg')
            ]
        ];

        $response = $this->actingAs($this->user)
            ->from(route('admin.news.edit', $news))
            ->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.edit', $news));
        $response->assertSessionHas('error');

        // Should still be 7 images (not 8)
        $this->assertEquals(7, $news->fresh()->images()->count());
    }

    #[Test]
    public function it_can_delete_news_image()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id
        ]);

        $image = $news->images()->create([
            'image_path' => 'news/slides/test.jpg',
            'order' => 0
        ]);

        Storage::disk('public')->put('news/slides/test.jpg', 'fake content');

        $response = $this->actingAs($this->user)
            ->delete(route('admin.news.delete-image', $image));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('news_images', [
            'id' => $image->id
        ]);

        Storage::disk('public')->assertMissing('news/slides/test.jpg');
    }

    #[Test]
    public function it_sanitizes_html_content()
    {
        $data = [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'content' => '<p>Safe content</p><script>alert("xss")</script>',
            'category' => 'Berita',
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->withoutMiddleware()
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news = News::where('slug', 'test-title')->first();
        $this->assertNotNull($news);
        $this->assertStringNotContainsString('<script>', $news->content);
    }

    #[Test]
    public function it_auto_generates_slug_if_empty()
    {
        $data = [
            'title' => 'Test Berita Tanpa Slug',
            'slug' => '', // Empty slug
            'content' => '<p>Content</p>',
            'category' => 'Berita',
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));

        $this->assertDatabaseHas('news', [
            'title' => 'Test Berita Tanpa Slug',
            'slug' => 'test-berita-tanpa-slug'
        ]);
    }

    #[Test]
    public function it_sets_default_author_if_empty()
    {
        $data = [
            'title' => 'Test Title',
            'slug' => 'test-title',
            'content' => '<p>Content</p>',
            'category' => 'Berita',
            'author' => '', // Empty author
            'featured_image' => UploadedFile::fake()->image('news.jpg'),
            'is_published' => true
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));

        $news = News::where('slug', 'test-title')->first();
        $this->assertEquals($this->user->name, $news->author);
        $this->assertEquals($this->user->id, $news->author_id);
    }

    #[Test]
    public function form_has_correct_data_attributes()
    {
        $news = News::factory()->create([
            'author_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->withoutMiddleware(\App\Http\Middleware\OptimizeResponse::class)
            ->get(route('admin.news.edit', $news));

        $response->assertStatus(200);
        $response->assertSee('data-edit-mode="true"', false);
        $response->assertSee('data-news-id="' . $news->id . '"', false);
        $response->assertSee('data-upload-url', false);
    }

    #[Test]
    public function create_form_has_correct_data_attributes()
    {
        $response = $this->actingAs($this->user)
            ->withoutMiddleware(\App\Http\Middleware\OptimizeResponse::class)
            ->get(route('admin.news.create'));

        $response->assertStatus(200);
        $response->assertSee('data-edit-mode="false"', false);
        $response->assertSee('data-news-id="new"', false);
    }
}
