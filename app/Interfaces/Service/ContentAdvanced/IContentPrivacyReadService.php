<?php

namespace App\Interfaces\Service\ContentAdvanced;

interface IContentPrivacyReadService
{
    public function getContentPrivacy(int $course_id, int $privacy, int $perPage, int $page);
}
