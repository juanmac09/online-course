<?php

namespace App\Interfaces\Repository\Comments;

interface ICommentsReadRepository
{
    public function getCommentsByContent(int $content_id, int $perPage, int $page);
}
