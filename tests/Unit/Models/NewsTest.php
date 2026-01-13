<?php

namespace Tests\Unit\Models;

use App\Models\News;
use App\Models\NewsImage;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsTest extends TestCase
{
    #[Test]
    public function slug_is_generated_from_title(): void
    {
        $news = News::factory()->create([
            'title' => 'Berita Terbaru Tentang Koperasi',
        ]);

        $this->assertEquals('berita-terbaru-tentang-koperasi', $news->slug);
    }

    #[Test]
    public function slug_handles_special_characters(): void
    {
        $news = News::factory()->create([
            'title' => 'Berita & Pengumuman Penting!',
        ]);

        $this->assertMatchesRegularExpression('/^[a-z0-9-]+$/', $news->slug);
    }

    #[Test]
    public function scope_published_filters_by_is_published(): void
    {
        News::factory()->count(3)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        News::factory()->count(2)->create([
            'is_published' => false,
            'published_at' => now()->subDay(),
        ]);

        $publishedNews = News::published()->get();

        $this->assertCount(3, $publishedNews);
        $publishedNews->each(fn($news) => $this->assertTrue($news->is_published));
    }

    #[Test]
    public function scope_published_filters_by_published_at(): void
    {
        News::factory()->count(2)->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        News::factory()->count(3)->create([
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);

        $publishedNews = News::published()->get();

        $this->assertCount(2, $publishedNews);
        $publishedNews->each(fn($news) => $this->assertTrue($news->published_at <= now()));
    }

    #[Test]
    public function scope_published_requires_both_conditions(): void
    {
        // Published and past date - should be included
        News::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        // Not published but past date - should NOT be included
        News::factory()->create([
            'is_published' => false,
            'published_at' => now()->subDay(),
        ]);
        // Published but future date - should NOT be included
        News::factory()->create([
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);

        $publishedNews = News::published()->get();

        $this->assertCount(1, $publishedNews);
    }

    #[Test]
    public function user_relationship_returns_author(): void
    {
        $author = User::factory()->create();
        $news = News::factory()->create(['author_id' => $author->id]);

        $this->assertInstanceOf(User::class, $news->user);
        $this->assertEquals($author->id, $news->user->id);
    }

    #[Test]
    public function images_relationship_returns_news_images(): void
    {
        $news = News::factory()->create();

        NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/image1.jpg',
            'order' => 1,
        ]);
        NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/image2.jpg',
            'order' => 2,
        ]);

        $this->assertCount(2, $news->images);
        $this->assertInstanceOf(NewsImage::class, $news->images->first());
    }

    #[Test]
    public function images_relationship_is_ordered_by_order_column(): void
    {
        $news = News::factory()->create();

        NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/image3.jpg',
            'order' => 3,
        ]);
        NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/image1.jpg',
            'order' => 1,
        ]);
        NewsImage::create([
            'news_id' => $news->id,
            'image_path' => 'news/image2.jpg',
            'order' => 2,
        ]);

        $images = $news->images;

        $this->assertEquals(1, $images[0]->order);
        $this->assertEquals(2, $images[1]->order);
        $this->assertEquals(3, $images[2]->order);
    }
}
