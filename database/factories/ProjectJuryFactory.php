<?php

namespace Database\Factories;

use App\Enums\ProjectJuriesRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectJury>
 */
class ProjectJuryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $juryRolesList = ProjectJuriesRole::values();
        return [
            'role' => $juryRolesList[array_rand($juryRolesList)],
        ];
    }
}
