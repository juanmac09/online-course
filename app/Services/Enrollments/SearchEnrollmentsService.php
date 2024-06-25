<?php

namespace App\Services\Enrollments;

use App\Interfaces\Repository\Enrollments\ISearchEnrollmentsRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Service\Enrollments\ISearchEnrollmentsService;

class SearchEnrollmentsService implements ISearchEnrollmentsService
{
    public $userRepository;
    public $enrollmentsRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IUserProfileRepository $userRepository, ISearchEnrollmentsRepository $enrollmentsRepository)
    {
        $this->userRepository = $userRepository;
        $this->enrollmentsRepository = $enrollmentsRepository;
    }


    /**
     * Finds enrollments by keywords for a specific user.
     *
     * @param int $userId The ID of the user whose enrollments to search.
     * @param string $keyword The keyword to search for in the enrollments.
     * @param int $perPage The number of enrollments to return per page.
     * @param int $page The page number of the enrollments to return.
     *
     * @return array An array of enrollments matching the search criteria.
     */
    public function findEnrollmentsByKeywords(int $userId, string $keyword, int $perPage, int $page)
    {
        $user = $this->userRepository->getUserForId($userId);
        $enrollments = $this->enrollmentsRepository->findEnrollmentsByKeywords($user, $keyword, $perPage, $page);
        return $enrollments;
    }

    /**
     * Retrieves the public and active enrollments for a specific user.
     *
     * @param int $userId The ID of the user whose enrollments to retrieve.
     * @param int $perPage The number of enrollments to return per page.
     * @param int $page The page number of the enrollments to return.
     *
     * @return array An array of public and active enrollments matching the search criteria.
     */
    public function getPublicAndActiveEnrollments(int $userId, int $perPage, int $page)
    {
        $user = $this->userRepository->getUserForId($userId);
        $enrollments = $this->enrollmentsRepository->getPublicAndActiveEnrollments($user, $perPage, $page);
        return $enrollments;
    }
}
