<?php

namespace Database\Factories;

use App\Enums\UserRole;
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
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserRole::values()[random_int(0,2)] ,
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
    //states
    public function teacher(): static //makes role into teacher 
    {
        return $this->state( fn (array $attributes)=>[
            'role' => UserRole::TEACHER,
        ]);
    }
    public function admin(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'role' => UserRole::ADMIN,
        ]);
    }
    public function company(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'role' => UserRole::COMPANY,
        ]);
    }
    public function owner(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'role' => UserRole::OWNER,
        ]);
    }
}
