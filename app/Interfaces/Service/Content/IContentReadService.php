<?php

namespace App\Interfaces\Service\Content;

interface IContentReadService
{
    public function getContentForCourse(int $id,int $perPage, int $page);
}
