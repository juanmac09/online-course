<?php

namespace App\Interfaces\Repository\CourseAdvanced;

interface ICourseStatusReadRepository
{
    public function getCourseStatus(int $user_id, int $status,int $perPage, int $page);
}
