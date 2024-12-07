<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectSubmit>
 */
class ProjectSubmitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'validated' => [true,false][random_int(0,1)],
        ];
    }
    
    //states
    public function valid(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'validated' => true,
        ]);
    }
    public function invalid(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'validated' => false,
        ]);
    }
}
