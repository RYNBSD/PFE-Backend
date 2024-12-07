<?php

namespace Database\Factories;

use App\Enums\TeacherGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradesList = TeacherGrade::values();
        return [
            //using array_rand instead of random_int(0,4) because it can handle a change of the enum length
            'grade' => $gradesList[array_rand($gradesList)],
            'recruitement_date' => fake()->dateTimeInInterval( '-40 years','+39 years',  null),
        ];
    }

    //states
    public function maa() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => TeacherGrade::MAA,
        ]);
    }
    public function mab() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => TeacherGrade::MAB,
        ]);
    }
    public function mca() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => TeacherGrade::MCA,
        ]);
    }
    public function mcb() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => TeacherGrade::MCB,
        ]);
    }
    public function professor() : static 
    {
        return $this->state( fn (array $attributes)=>[
            'grade' => TeacherGrade::PROFESSOR,
        ]);
    }
}
