<?php

namespace Database\Factories;

use App\Enums\ProjectPropositionsStatus;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectProposition>
 */
class ProjectPropositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusList = ProjectPropositionsStatus::values();
        return [
            'status' => $statusList[array_rand($statusList)],
        ];
    }
    
    //states
    public function pending(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectPropositionsStatus::PENDING,
        ]);
    }
    public function validated(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectPropositionsStatus::VALIDATED,
        ]);
    }
    public function rejected(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectPropositionsStatus::REJECTED,
        ]);
    }
}
