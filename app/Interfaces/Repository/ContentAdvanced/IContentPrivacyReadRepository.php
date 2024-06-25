<?php

namespace App\Interfaces\Repository\ContentAdvanced;

interface IContentPrivacyReadRepository 
{
    public function getContentPrivacy(int $course_id, int $privacy,int $perPage, int $page);
}
