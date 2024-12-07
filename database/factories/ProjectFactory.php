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
            'description' => 'Ratione possimus molestiae quis et. Error et accusantium aut eum. Dolorem rerum perferendis delectus pariatur qui repellat maxime. Quisquam aut sint ad id similique sit.',
            'type' => $projectTypeLists[array_rand($projectTypeLists)],
            'status' =>$projectStatusList[array_rand($projectStatusList)],
            
        ];
    }
}
