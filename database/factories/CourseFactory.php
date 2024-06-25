<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(20),
            'description' => fake()->realText(120),
            'user_id' =>  User::all()->random(),
        ];
    }




    /**
     * Configure the factory after creating a new model instance.
     *
     * @return void
     */
    public function configure()
    {
        return $this->afterCreating(function (Course $course) {
            $users = User::inRandomOrder()->take(rand(1, 5))->pluck('id');
            $course->users()->sync($users);
        });
    }
}
