<?php

namespace Database\Factories;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => Complaint::generateTicketNumber(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'identity_number' => fake()->numerify('################'),
            'type' => fake()->randomElement(['fraud', 'violation', 'ethics', 'abuse', 'safety', 'other']),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'reported_person' => fake()->name(),
            'reported_department' => fake()->word(),
            'incident_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'incident_location' => fake()->address(),
            'attachments' => null,
            'is_anonymous' => false,
            'status' => 'pending',
            'admin_notes' => null,
            'resolved_at' => null,
        ];
    }

    /**
     * Indicate that the complaint is anonymous.
     */
    public function anonymous(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_anonymous' => true,
            'name' => null,
            'email' => null,
            'phone' => null,
        ]);
    }

    /**
     * Indicate that the complaint is in review.
     */
    public function inReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'in_review',
        ]);
    }

    /**
     * Indicate that the complaint is being investigated.
     */
    public function investigating(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'investigating',
        ]);
    }

    /**
     * Indicate that the complaint is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    /**
     * Indicate that the complaint is closed.
     */
    public function closed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'closed',
            'resolved_at' => now(),
        ]);
    }
}
