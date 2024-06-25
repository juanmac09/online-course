<?php

namespace App\Services\Comments;

use App\Interfaces\Repository\Comments\ICommentsWriteRepository;
use App\Interfaces\Service\Comments\ICommentsWriteService;

class CommentsWriteService implements ICommentsWriteService
{
    public $commentsRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ICommentsWriteRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * Create a new comment.
     *
     * @param int $userId The ID of the user creating the comment.
     * @param int $contentId The ID of the content to which the comment is attached.
     * @param string $comment The text of the comment.
     *
     * @return mixed The ID of the newly created comment.
     */
    public function createComments(int $userId, int $contentId, string $comment)
    {
        return $this->commentsRepository->createComments($userId, $contentId, $comment);
    }


    /**
     * Update an existing comment.
     *
     * @param int $commentId The ID of the comment to be updated.
     * @param string $comment The new text of the comment.
     *
     * @return mixed The updated comment's ID.
     */
    public function updateComments(int $commentId, string $comment)
    {
        return $this->commentsRepository->updateComments($commentId, $comment);
    }

    /**
     * Disable an existing comment.
     *
     * @param int $commentId The ID of the comment to be disabled.
     *
     * @return mixed The comment's ID upon successful operation.
     */
    public function disableComments(int $commentId)
    {
        return $this->commentsRepository->disableComments($commentId);
    }
}
