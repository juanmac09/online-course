<?php

namespace App\Interfaces\Repository\CourseAdvanced;

use App\Models\User;

interface IAdvancedCourseRepository
{
    public function getEnrolledCourses(User $user,int $perPage, int $page);
}
