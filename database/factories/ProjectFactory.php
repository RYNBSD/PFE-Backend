<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectTypeLists = ProjectType::values();
        $projectStatusList = ProjectStatus::values();
        return [
            'title' => fake()->title(),
            'description' => fake()->paragraph(2),
            'type' => $projectTypeLists[array_rand($projectTypeLists)],
            'status' =>$projectStatusList[array_rand($projectStatusList)],
        ];
    }
    
    //states
    public function _1275(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'type' => ProjectType::_1275,
        ]);
    }
    public function classical(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'type' => ProjectType::CLASSICAL,
        ]);
    }
    public function proposed(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectStatus::PROPOSED,
        ]);
    }
    public function assigned(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectStatus::ASSIGNED,
        ]);
    }
    public function approved(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectStatus::APPROVED,
        ]);
    }
    public function completed(): static 
    {
        return $this->state( fn (array $attributes)=>[
            'status' => ProjectStatus::COMPLETED,
        ]);
    }
}
