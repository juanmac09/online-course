<?php

namespace App\Interfaces\Service\Comments;

interface ICommentsWriteService
{
    public function createComments(int $userId, int $contentId, string $comment);
    public function updateComments(int $commentId, string $comment);
    public function disableComments(int $commentId);
}
