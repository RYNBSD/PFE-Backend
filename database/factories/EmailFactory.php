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
            'subject' => 'Lorem Ipsum.',
            'content' => 'Consequatur omnis dolores temporibus dicta ex placeat nemo iste. Sapiente quos voluptas et asperiores aut occaecati sed necessitatibus. Aut vitae enim assumenda quis. Rem occaecati aliquam incidunt fugiat omnis esse nemo',
            'status' => $statusList[array_rand($statusList)],
        ];
    }
}
