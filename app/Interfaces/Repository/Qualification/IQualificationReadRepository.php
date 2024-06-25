<?php

namespace App\Interfaces\Repository\Qualification;

use App\Models\Course;

interface IQualificationReadRepository
{
    public function getAllQualificationsByCourse(int $course_id);
}
