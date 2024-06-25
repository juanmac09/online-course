<?php

namespace App\Interfaces\Repository\Comments;

interface ICommentsRepository
{
    public function commentById(int $id);
}
