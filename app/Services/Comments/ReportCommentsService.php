<?php

namespace App\Services\Comments;

use App\Interfaces\Repository\Comments\IReportCommentsRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Service\Comments\IReportCommentsService;

class ReportCommentsService implements IReportCommentsService
{
    public $commentRepository;
    public $userRepository;

    public function __construct(IReportCommentsRepository $commentRepository, IUserProfileRepository $userRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }



    /**
     * Get comments by a specific user.
     *
     * @param int $userId The ID of the user whose comments to retrieve.
     * @param int $perPage The number of comments to return per page.
     * @param int $page The page number of comments to return.
     *
     * @return array An array of comments belonging to the specified user, paginated according to the provided parameters.
     */
    public function getCommentsByUser(int $userId, int $perPage, int $page)
    {
        $user = $this->userRepository->getUserForId($userId);
        return $this->commentRepository->getCommentsByUser($user, $perPage, $page);
    }
}
