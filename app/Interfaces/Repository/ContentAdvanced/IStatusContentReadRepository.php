<?php

namespace App\Interfaces\Repository\ContentAdvanced;

interface IStatusContentReadRepository
{
    public function getContentStatus(int $course_id, int $status,int $perPage, int $page);
}
