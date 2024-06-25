<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICourseStatusReadService
{
    public function getCourseStatus(int $user_id,int  $status,int $perPage, int $page);
}
