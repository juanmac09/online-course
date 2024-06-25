<?php

namespace Database\Factories;

use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Qualification>
 */
class QualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $qualification = $this->faker->numberBetween(1, 5);
        $user_id = $this->faker->numberBetween(1, 11);
        $course_id = $this->faker->numberBetween(1, 500);

        $existingQualification = Qualification::where('user_id', $user_id)
                                             ->where('course_id', $course_id)
                                             ->exists();

        if ($existingQualification) {
            return [];
        }

        return [
            'qualification' => $qualification,
            'user_id' => $user_id,
            'course_id' => $course_id,
        ];
    }
}
