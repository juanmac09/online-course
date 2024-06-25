<?php

namespace App\Interfaces\Service\Comments;

interface IReportCommentsService
{
    public function getCommentsByUser(int $userId,int $perPage, int $page);
}
