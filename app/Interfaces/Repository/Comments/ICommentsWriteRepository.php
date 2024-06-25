<?php

namespace App\Interfaces\Repository\Comments;

interface ICommentsWriteRepository
{
    public function createComments(int $user_id, int $content_id, string $comment);
    public function updateComments(int $comment_id, string $comment);
    public function disableComments(int $comment_id);
}
