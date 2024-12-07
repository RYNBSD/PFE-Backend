<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $directions = ['N', 'S'];
        return [
            // random number like N001, S210 or N 104
            'room' => $directions[array_rand($directions)].
                      random_int(0,2).
                      str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT),
        ];
    }
}
