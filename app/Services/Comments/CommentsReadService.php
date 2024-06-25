<?php

namespace App\Services\Comments;

use App\Interfaces\Repository\Comments\ICommentsReadRepository;
use App\Interfaces\Service\Comments\ICommentsReadService;

class CommentsReadService implements ICommentsReadService
{
    public $commentsRepository;

    public function __construct(ICommentsReadRepository $commentsRepository)
    {
        $this->commentsRepository = $commentsRepository;
    }


    /**
     * Get comments by content id.
     *
     * @param int $content_id The id of the content to get comments for.
     * @param int $perPage The number of comments to return per page.
     * @param int $page The page number of comments to return.
     *
     * @return array An array of comments associated with the given content id.
     */
    public function getCommentsByContent(int $content_id, int $perPage, int $page)
    {
        return $this->commentsRepository->getCommentsByContent($content_id, $perPage, $page);
    }
}
