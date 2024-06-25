<?php

namespace App\Interfaces\Repository\CourseAdvanced;

interface ICoursePrivacyWriteRepository
{
    public function changeCoursePrivacy(int $course_id, int $privacy);
}
