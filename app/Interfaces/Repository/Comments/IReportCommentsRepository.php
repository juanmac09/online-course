<?php

namespace App\Interfaces\Repository\Comments;

use App\Models\User;

interface IReportCommentsRepository
{
    public function getCommentsByUser(User $user,int $perPage, int $page);
}
