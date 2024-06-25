<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ICoursePublicWriteService
{
    public function makePublicACourse(int $courseId);
}
