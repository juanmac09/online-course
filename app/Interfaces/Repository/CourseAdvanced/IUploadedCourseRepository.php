<?php

namespace App\Interfaces\Repository\CourseAdvanced;

use App\Models\User;

interface IUploadedCourseRepository
{
    public function getUploadedCourses(User $user,int $perPage, int $page);
}
