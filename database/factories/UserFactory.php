<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
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
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'firstName' => fake()->name(),
            'lastName' => fake()->name(),
            'username' => fake()->userName(),
            'clinic_id' => Clinic::factory()->create(),
            'is_banned' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign a random role to the user
     */
    public function withRole(?array $rolesNames): static
    {
        if(isset($rolesNames)){
            return $this->afterCreating(function ($user) use ($rolesNames) {
                $user->assignRole($rolesNames);
            });
        }


        $roles = ['admin', 'doctor', 'patient']; // Add your roles here

        return $this->afterCreating(function ($user) use ($roles) {
            $user->assignRole(fake()->randomElement($roles));
        });
    }
}
