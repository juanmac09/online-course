<?php

namespace App\Interfaces\Service\Course;

interface ICourseWriteService
{
    public function createCourse(array $courseData,int $user_id);
    public function updateCourse(int $id,array $courseData);
    public function disableCourse(int $id);
}
