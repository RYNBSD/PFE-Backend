<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectRegistration>
 */
class ProjectRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now','+2 months');
        return [
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeInInterval($startDate,'+2 months')
        ];
    }
}
