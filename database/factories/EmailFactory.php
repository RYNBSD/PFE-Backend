<?php

namespace Database\Factories;

use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusList = EmailStatus::values();
        return [
            'subject' => fake()->sentence,
            'content' => fake()->paragraph(4),
            'status' => $statusList[array_rand($statusList)],
        ];
    }
    
    //states
    public function sent(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => EmailStatus::SENT,
        ]);
    }
    public function pending(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => EmailStatus::PENDING,
        ]);
    }
    public function failed(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => EmailStatus::FAILED,
        ]);
    }
}
