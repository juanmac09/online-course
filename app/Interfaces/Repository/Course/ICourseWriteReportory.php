<?php

namespace App\Interfaces\Repository\Course;

interface ICourseWriteReportory
{
    public function createCourse(array $courseData);
    public function updateCourse(int $id,array $courseData);
    public function disableCourse(int $id);
}
