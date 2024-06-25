<?php

namespace App\Repository\Enrollments;

use App\Interfaces\Repository\Enrollments\ISearchEnrollmentsRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\User;

class SearchEnrollmentsRepository implements ISearchEnrollmentsRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * This method is used to find enrollments based on keywords.
     *
     * @param User $user The user for whom the enrollments are being searched.
     * @param string $keyword The keyword to search for in the enrollments.
     * @param int $perPage The number of enrollments to display per page.
     * @param int $page The page number for pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator A paginated collection of courses that match the keyword.
     *
     * @throws \Exception If any error occurs during the search process.
     */
    public function findEnrollmentsByKeywords(User $user, string $keyword, int $perPage, int $page)
    {
        $courses = $user->courses()->where('courses.status', 1)->where('courses.public', 1)->whereAny([
            'title',
            'description',
        ], 'LIKE', "%$keyword%");
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }


    /**
     * This method is used to find public and active enrollments for a given user.
     *
     * @param User $user The user for whom the enrollments are being searched.
     * @param int $perPage The number of enrollments to display per page.
     * @param int $page The page number for pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator A paginated collection of courses that are public and active.
     */
    public function getPublicAndActiveEnrollments(User $user, int $perPage, int $page)
    {
        $courses = $user->courses()->where('courses.status', 1)->where('courses.public', 1);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
