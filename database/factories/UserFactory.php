<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $editorRole = \App\Models\Role::where('name', 'editor')->first();
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => $editorRole?->id,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a super admin.
     */
    public function superAdmin(): static
    {
        $superAdminRole = \App\Models\Role::where('name', User::ROLE_SUPER_ADMIN)->first();
        return $this->state(fn(array $attributes) => [
            'role_id' => $superAdminRole?->id,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        $adminRole = \App\Models\Role::where('name', User::ROLE_ADMIN)->first();
        return $this->state(fn(array $attributes) => [
            'role_id' => $adminRole?->id,
        ]);
    }

    /**
     * Indicate that the user is an editor.
     */
    public function editor(): static
    {
        $editorRole = \App\Models\Role::where('name', User::ROLE_EDITOR)->first();
        return $this->state(fn(array $attributes) => [
            'role_id' => $editorRole?->id,
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
