<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Pusat', 'Cabang', 'Kas']),
            'type' => fake()->randomElement(['pusat', 'cabang', 'kas', 'kas_keliling']),
            'address' => fake()->address(),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'photo' => null,
            'latitude' => fake()->latitude(-8.5, -6.0),
            'longitude' => fake()->longitude(106.0, 112.0),
            'google_maps_url' => fake()->optional()->url(),
            'operational_hours' => [
                'senin' => '08:00 - 16:00',
                'selasa' => '08:00 - 16:00',
                'rabu' => '08:00 - 16:00',
                'kamis' => '08:00 - 16:00',
                'jumat' => '08:00 - 16:00',
            ],
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the office is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the office is the main office.
     */
    public function pusat(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'pusat',
            'name' => 'Kantor Pusat ' . fake()->company(),
        ]);
    }

    /**
     * Indicate that the office is a branch office.
     */
    public function cabang(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'cabang',
            'name' => 'Kantor Cabang ' . fake()->city(),
        ]);
    }

    /**
     * Indicate that the office is a cash office.
     */
    public function kas(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'kas',
            'name' => 'Kantor Kas ' . fake()->city(),
        ]);
    }

    /**
     * Indicate that the office is a mobile cash unit.
     */
    public function kasKeliling(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'kas_keliling',
            'name' => 'Kas Keliling ' . fake()->city(),
        ]);
    }
}
