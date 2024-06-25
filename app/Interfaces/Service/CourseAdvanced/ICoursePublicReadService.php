<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICoursePublicReadService
{
    public function getPublicCourses(int $user_id,int $perPage, int $page);
}
