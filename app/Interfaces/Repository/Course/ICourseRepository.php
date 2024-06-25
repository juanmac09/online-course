<?php

namespace App\Interfaces\Repository\Course;

interface ICourseRepository
{
    public function courseById(int $courseId);
}
