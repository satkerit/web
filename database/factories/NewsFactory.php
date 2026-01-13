<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(5, true),
            'excerpt' => fake()->paragraph(),
            'featured_image' => null,
            'category' => fake()->randomElement(['Berita', 'Pengumuman', 'Artikel', 'Promo']),
            'is_published' => true,
            'published_at' => now(),
            'author_id' => User::factory(),
            'author' => fake()->name(),
        ];
    }

    /**
     * Indicate that the news is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the news is scheduled for future publication.
     */
    public function scheduled(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);
    }

    /**
     * Indicate that the news is in a specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => $category,
        ]);
    }
}
