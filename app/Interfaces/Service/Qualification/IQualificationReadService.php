<?php

namespace App\Interfaces\Service\Qualification;

interface IQualificationReadService
{
    public function getAllQualificationsByCourse(int $course_id);
}
