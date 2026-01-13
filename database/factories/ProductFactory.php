<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['simpanan_syariah', 'pembiayaan_syariah', 'deposito'];

        return [
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement($types),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'interest_rate' => $this->faker->randomElement(['3% - 5%', '4% - 6%', '5% - 7%', null]),
            'features' => $this->faker->randomElement([
                ['Feature 1', 'Feature 2', 'Feature 3'],
                ['Feature A', 'Feature B'],
                null
            ]),
            'requirements' => $this->faker->randomElement([
                ['Requirement 1', 'Requirement 2'],
                ['Requirement A', 'Requirement B', 'Requirement C'],
                null
            ]),
            'benefits' => $this->faker->randomElement([
                ['Benefit 1', 'Benefit 2'],
                ['Benefit A', 'Benefit B', 'Benefit C'],
                null
            ]),
            'image' => null,
            'image_alt' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'order_position' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is simpanan syariah.
     */
    public function simpananSyariah(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'simpanan_syariah',
        ]);
    }

    /**
     * Indicate that the product is pembiayaan syariah.
     */
    public function pembiayaanSyariah(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'pembiayaan_syariah',
        ]);
    }

    /**
     * Indicate that the product is deposito.
     */
    public function deposito(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'deposito',
        ]);
    }
}
