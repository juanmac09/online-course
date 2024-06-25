<?php

namespace App\Interfaces\Service\Comments;

interface ICommentsReadService
{
    public function getCommentsByContent(int $content_id,int $perPage, int $page);
}
