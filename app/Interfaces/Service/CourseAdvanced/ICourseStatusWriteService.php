<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICourseStatusWriteService
{
    public function changeCourseStatus(int $course_id, int $status);
}
