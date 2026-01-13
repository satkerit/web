<?php

namespace Database\Factories;

use App\Models\BoardMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoardMember>
 */
class BoardMemberFactory extends Factory
{
    protected $model = BoardMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['komisaris', 'direksi', 'pengawas_syariah'];
        $type = fake()->randomElement($types);

        $positions = [
            'komisaris' => ['Komisaris Utama', 'Komisaris', 'Komisaris Independen'],
            'direksi' => ['Direktur Utama', 'Direktur', 'Direktur Operasional', 'Direktur Keuangan'],
            'pengawas_syariah' => ['Ketua DPS', 'Anggota DPS'],
        ];

        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement($positions[$type]),
            'type' => $type,
            'photo' => null,
            'biography' => fake()->paragraphs(2, true),
            'education' => [
                fake()->randomElement(['S1', 'S2', 'S3']) . ' ' . fake()->randomElement(['Ekonomi', 'Manajemen', 'Akuntansi', 'Hukum']) . ' - ' . fake()->company(),
                fake()->randomElement(['S1', 'S2']) . ' ' . fake()->randomElement(['Ekonomi', 'Manajemen', 'Akuntansi']) . ' - ' . fake()->company(),
            ],
            'experience' => [
                fake()->jobTitle() . ' di ' . fake()->company() . ' (' . fake()->year() . '-' . fake()->year() . ')',
                fake()->jobTitle() . ' di ' . fake()->company() . ' (' . fake()->year() . '-' . fake()->year() . ')',
            ],
            'order_position' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the board member is a komisaris.
     */
    public function komisaris(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'komisaris',
            'position' => fake()->randomElement(['Komisaris Utama', 'Komisaris', 'Komisaris Independen']),
        ]);
    }

    /**
     * Indicate that the board member is a direksi.
     */
    public function direksi(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'direksi',
            'position' => fake()->randomElement(['Direktur Utama', 'Direktur', 'Direktur Operasional', 'Direktur Keuangan']),
        ]);
    }

    /**
     * Indicate that the board member is a pengawas syariah.
     */
    public function pengawasSyariah(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'pengawas_syariah',
            'position' => fake()->randomElement(['Ketua DPS', 'Anggota DPS']),
        ]);
    }
}
