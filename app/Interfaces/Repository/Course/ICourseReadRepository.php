<?php

namespace App\Interfaces\Repository\Course;

interface ICourseReadRepository
{
    public function getAllCourse(int $perPage, int $page);
}
