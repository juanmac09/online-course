<?php

namespace Database\Factories;

use App\Models\CourseContent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comments>
 */
class CommentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake() -> realText(150),
            'user_id' => User::all() -> random(),
            'content_id' => CourseContent:: all() -> random(),
        ];
    }
}
