<?php

namespace App\Interfaces\Repository\CourseAdvanced;

interface ICourseStatusWriteRepository
{
    public function changeCourseStatus(int $course_id, int $status);
}
