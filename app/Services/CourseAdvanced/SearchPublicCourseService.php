<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ISearchPublicCourseRepository;
use App\Interfaces\Service\CourseAdvanced\ISearchPublicCourseService;

class SearchPublicCourseService implements ISearchPublicCourseService
{
    public $courseRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(ISearchPublicCourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Finds courses by a given keyword.
     *
     * @param string $keyword The keyword to search for.
     * @param int $perPage The number of courses to return per page.
     * @param int $page The page number to return.
     *
     * @return array An array of courses matching the given keyword, sorted by relevance.
     */
    public function findByKeyword(string $keyword, int $perPage, int $page)
    {
        return $this->courseRepository->findByKeyword($keyword, $perPage, $page);
    }


    /**
     * Retrieves public and active courses for a given user.
     *
     * @param int $user_id The ID of the user for whom to retrieve courses.
     * @param int $perPage The number of courses to return per page.
     * @param int $page The page number to return.
     *
     * @return array An array of public and active courses for the specified user, sorted by relevance.
     */
    public function getPublicAndActiveCourses(int $user_id, int $perPage, int $page)
    {
        return $this->courseRepository->getPublicAndActiveCourses($user_id, $perPage,  $page);
    }
}
