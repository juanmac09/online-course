<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICoursePrivateWriteService
{
    public function makePrivateACourse(int $courseId);
}
