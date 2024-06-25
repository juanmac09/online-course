<?php

namespace App\Interfaces\Service\CourseAdvanced;

use App\Models\User;

interface IAdvancedCourseService
{
    public function getEnrolledCourses(?User $user,?int $user_id,int $perPage, int $page);
}
