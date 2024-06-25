<?php

namespace Database\Seeders;

use App\Models\Comments;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Qualification;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this -> call([RoleSeeder::class]);
        
        User::factory()->create([
            'name' => 'Juan David Romero Sánchez',
            'email' => 'mateoromero0910@gmail.com',
            'password' => Hash::make('juan0910'),
            'role_id' => 1
        ]);
        
        User::factory(10)->create();
        Course::factory(500) -> create();
        CourseContent::factory(1000) -> create();
        Comments::factory(2000) -> create();
        Qualification::factory(20000) -> create();

    }
}
