<?php

namespace App\Repository\Comments;

use App\Interfaces\Repository\Comments\ICommentsReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Comments;

class CommentsReadRepository implements ICommentsReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Get comments by content id, with pagination.
     *
     * @param int $content_id The id of the content to get comments for.
     * @param int $perPage The number of comments to return per page.
     * @param int $page The page number to return.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of comments.
     */
    public function getCommentsByContent(int $content_id, int $perPage, int $page)
    {
        $comments =  Comments::where('content_id', $content_id)->where('status', 1)->orderBy('created_at', 'desc');
        return $this->paginationRepository->paginate($comments, $perPage, $page);
    }
}
