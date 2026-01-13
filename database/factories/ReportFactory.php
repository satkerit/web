<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['keuangan_publikasi', 'tata_kelola', 'tahunan', 'tahunan_berkelanjutan'];
        $type = fake()->randomElement($types);

        return [
            'title' => fake()->sentence(4),
            'type' => $type,
            'year' => fake()->numberBetween(2020, 2025),
            'quarter' => in_array($type, ['keuangan_publikasi']) ? fake()->numberBetween(1, 4) : null,
            'file_path' => 'reports/' . fake()->uuid() . '.pdf',
            'file_size' => fake()->numberBetween(100000, 5000000),
            'description' => fake()->sentence(),
            'is_published' => true,
            'posting_mode' => 'auto',
            'posted_at' => now(),
            'scheduled_at' => null,
            'preview_count' => fake()->numberBetween(0, 100),
            'download_count' => fake()->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the report is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => false,
            'posted_at' => null,
        ]);
    }

    /**
     * Indicate that the report is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => true,
            'posting_mode' => 'manual',
            'scheduled_at' => now()->addDays(7),
            'posted_at' => now()->addDays(7),
        ]);
    }

    /**
     * Indicate that the report is a financial publication report.
     */
    public function keuanganPublikasi(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'keuangan_publikasi',
            'quarter' => fake()->numberBetween(1, 4),
        ]);
    }

    /**
     * Indicate that the report is a governance report.
     */
    public function tataKelola(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'tata_kelola',
            'quarter' => null,
        ]);
    }

    /**
     * Indicate that the report is an annual report.
     */
    public function tahunan(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'tahunan',
            'quarter' => null,
        ]);
    }
}
