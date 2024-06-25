<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICoursePrivateReadService
{
    public function getPrivateCourses(int $user_id,int $perPage, int $page);
}
