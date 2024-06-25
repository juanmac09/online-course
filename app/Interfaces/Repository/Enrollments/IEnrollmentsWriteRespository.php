<?php

namespace App\Interfaces\Repository\Enrollments;

interface IEnrollmentsWriteRespository
{
    public function enrollInCourse(int $user_id,int $courseId);
    public function unEnrollInCourse(int $user_id,int $courseId);
}
