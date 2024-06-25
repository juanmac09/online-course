<?php

namespace App\Interfaces\Service\Enrollments;

interface IEnrollmentsWriteService
{
    public function enrollInCourse(int $userId, int $courseId);
    public function unEnrollInCourse(int $userId, int $courseId);
}
