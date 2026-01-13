<?php

namespace Tests\Feature\Public;

use App\Models\News;
use App\Models\User;
use Tests\TestCase;

class NewsPagesTest extends TestCase
{
    /**
     * Test news listing page returns 200 status code.
     * Requirements: 2.6
     */
    public function test_news_listing_page_returns_successful_response(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/berita');

        $response->assertStatus(200);
    }

    /**
     * Test news detail with valid slug returns 200 status code.
     * Requirements: 2.7
     */
    public function test_news_detail_with_valid_slug_returns_successful_response(): void
    {
        $user = User::factory()->create();
        $news = News::factory()->create([
            'is_published' => true,
            'published_at' => now()->subDay(),
            'author_id' => $user->id,
        ]);

        $response = $this->withoutSecurityMiddleware()->get('/berita/' . $news->slug);

        $response->assertStatus(200);
    }

    /**
     * Test news detail with invalid slug returns 404 status code.
     * Requirements: 2.7
     */
    public function test_news_detail_with_invalid_slug_returns_404(): void
    {
        $response = $this->withoutSecurityMiddleware()->get('/berita/non-existent-news-slug');

        $response->assertStatus(404);
    }
}
