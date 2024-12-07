<?php

namespace Database\Factories;

use App\Enums\StudentMajor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $majorsList = StudentMajor::values();
        return [
            'major' => $majorsList[array_rand($majorsList)],
            'average_score' => random_int(500,2000)/100,
        ];
    }

    //states
    public function gl() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => StudentMajor::GL,
        ]);
    }
    public function ia() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => StudentMajor::IA,
        ]);
    }
    public function sic() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => StudentMajor::SIC,
        ]);
    }
    public function rsd() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => StudentMajor::RSD,
        ]);
    }
}
