<?php

namespace App\Interfaces\Service\ContentAdvanced;

interface ISearchContentService
{
    public function getPublicAndActiveContent(int $course_id,int $perPage, int $page);
}
