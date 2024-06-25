<?php

namespace App\Interfaces\Service\ContentAdvanced;

interface IStatusContentReadService
{
    public function getContentStatus(int $course_id, int $status, int $perPage, int $page);
}
