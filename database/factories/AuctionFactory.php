<?php

namespace Database\Factories;

use App\Models\Auction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    protected $model = Auction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startingPrice = fake()->numberBetween(100000000, 5000000000);

        return [
            'title' => fake()->sentence(4),
            'object_number' => fake()->numerify('OBJ-####-####'),
            'description' => fake()->paragraphs(3, true),
            'asset_type' => fake()->randomElement(array_keys(Auction::$assetTypes)),
            'certificate_type' => fake()->randomElement(array_keys(Auction::$certificateTypes)),
            'certificate_number' => fake()->numerify('##.##.##.##.#.#####'),
            'land_area' => fake()->numberBetween(50, 1000),
            'building_area' => fake()->numberBetween(30, 500),
            'debtor_name' => fake()->name(),
            'location' => fake()->address(),
            'starting_price' => $startingPrice,
            'estimated_price' => $startingPrice * 1.2,
            'auction_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'registration_deadline' => fake()->dateTimeBetween('now', '+1 week'),
            'auction_type' => fake()->randomElement(array_keys(Auction::$auctionTypes)),
            'auction_location' => fake()->city(),
            'deposit_amount' => $startingPrice * 0.2,
            'deposit_percentage' => 20,
            'bank_account' => fake()->numerify('###-###-####'),
            'bank_name' => fake()->randomElement(['BRI', 'BNI', 'Mandiri', 'BCA']),
            'account_holder' => fake()->company(),
            'terms_conditions' => fake()->paragraphs(2, true),
            'viewing_schedule' => fake()->sentence(),
            'kpknl_office' => fake()->city(),
            'risalah_number' => fake()->numerify('####/####'),
            'images' => null,
            'documents' => null,
            'status' => 'upcoming',
            'winning_bid' => null,
            'winner_name' => null,
            'sold_at' => null,
            'contact_person' => fake()->name(),
            'contact_phone' => fake()->phoneNumber(),
            'meta_description' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the auction is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'upcoming',
            'auction_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
        ]);
    }

    /**
     * Indicate that the auction is ongoing.
     */
    public function ongoing(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'ongoing',
            'auction_date' => now(),
        ]);
    }

    /**
     * Indicate that the auction is closed.
     */
    public function closed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'closed',
            'auction_date' => fake()->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    /**
     * Indicate that the auction is sold.
     */
    public function sold(): static
    {
        return $this->state(function (array $attributes) {
            $startingPrice = $attributes['starting_price'] ?? 100000000;
            return [
                'status' => 'sold',
                'winning_bid' => $startingPrice * fake()->randomFloat(2, 1.0, 1.5),
                'winner_name' => fake()->name(),
                'sold_at' => fake()->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    /**
     * Indicate that the auction is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
