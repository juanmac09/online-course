<?php

namespace App\Interfaces\Service\Course;

interface ICourseReadService
{
    public function getAllCourse(int $perPage, int $page);
}
