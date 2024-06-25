<?php

namespace App\Interfaces\Service\CourseAdvanced;

use App\Models\User;

interface IUploadedCourseService
{
    public function getUploadedCourses(?User $user, ?int $user_id, int $perPage, int $page);
}
