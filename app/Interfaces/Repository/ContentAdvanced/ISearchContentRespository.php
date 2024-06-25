<?php

namespace App\Interfaces\Repository\ContentAdvanced;

interface ISearchContentRespository
{
    public function getPublicAndActiveContent(int $course_id,int $perPage, int $page);
}
