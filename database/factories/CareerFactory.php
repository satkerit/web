<?php

namespace Database\Factories;

use App\Models\Career;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Career>
 */
class CareerFactory extends Factory
{
    protected $model = Career::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'department' => fake()->randomElement(['IT', 'Marketing', 'Finance', 'HR', 'Operations']),
            'location' => fake()->city(),
            'employment_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'internship']),
            'description' => fake()->paragraphs(3, true),
            'requirements' => fake()->paragraphs(2, true),
            'responsibilities' => fake()->paragraphs(2, true),
            'benefits' => fake()->paragraphs(1, true),
            'salary_range' => fake()->optional()->randomElement(['3-5 Juta', '5-8 Juta', '8-12 Juta', '12-15 Juta']),
            'deadline' => fake()->dateTimeBetween('now', '+3 months'),
            'is_active' => true,
            'order_position' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the career is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the career deadline has expired.
     */
    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'deadline' => fake()->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    /**
     * Indicate that the career is full time.
     */
    public function fullTime(): static
    {
        return $this->state(fn(array $attributes) => [
            'employment_type' => 'full_time',
        ]);
    }

    /**
     * Indicate that the career is an internship.
     */
    public function internship(): static
    {
        return $this->state(fn(array $attributes) => [
            'employment_type' => 'internship',
        ]);
    }

    /**
     * Indicate that the career has no deadline.
     */
    public function noDeadline(): static
    {
        return $this->state(fn(array $attributes) => [
            'deadline' => null,
        ]);
    }
}
