<?php

namespace App\Repository\Comments;

use App\Interfaces\Repository\Comments\IReportCommentsRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportCommentsRepository implements IReportCommentsRepository
{

    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }


    /**
     * Get comments by a specific user.
     *
     * @param User $user The user whose comments to retrieve.
     * @param int $perPage The number of comments to return per page.
     * @param int $page The page number of comments to return.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of comments.
     */
    public function getCommentsByUser(User $user, int $perPage, int $page):LengthAwarePaginator
    {
        $comments = $user->comments();

        return $this->paginationRepository->paginate($comments, $perPage, $page);
    }
}
