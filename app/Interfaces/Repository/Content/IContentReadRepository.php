<?php

namespace App\Interfaces\Repository\Content;

interface IContentReadRepository
{
    public function getContentForCourse(int $id,int $perPage, int $page);
}
