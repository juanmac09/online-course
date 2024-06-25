<?php

namespace App\Interfaces\Repository\CourseAdvanced;

interface ICoursePrivacyReadRepository
{
    public function getCoursePrivacy(int $user_id, int $privacy,int $perPage, int $page);
}
