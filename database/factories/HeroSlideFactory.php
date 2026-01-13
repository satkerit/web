<?php

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    protected $model = HeroSlide::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(),
            'image' => null,
            'link_url' => fake()->optional()->url(),
            'link_text' => fake()->optional()->words(2, true),
            'is_active' => true,
            'order_position' => fake()->numberBetween(1, 10),
            'transition_type' => fake()->randomElement(array_keys(HeroSlide::getTransitionTypes())),
            'transition_duration' => fake()->randomElement([3000, 4000, 5000, 6000]),
            'show_title' => true,
            'show_subtitle' => true,
            'show_button' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the hero slide is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the hero slide has a specific transition type.
     */
    public function transition(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'transition_type' => $type,
        ]);
    }

    /**
     * Indicate that the hero slide has no content overlay.
     */
    public function noOverlay(): static
    {
        return $this->state(fn(array $attributes) => [
            'show_title' => false,
            'show_subtitle' => false,
            'show_button' => false,
        ]);
    }
}
