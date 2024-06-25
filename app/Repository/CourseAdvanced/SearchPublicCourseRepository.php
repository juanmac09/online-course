<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ISearchPublicCourseRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Course;

class SearchPublicCourseRepository implements ISearchPublicCourseRepository
{

    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * This method is used to find courses based on a keyword in the title or description.
     * It only returns active and public courses.
     *
     * @param string $keyword The keyword to search for in the course title and description.
     * @param int $perPage The number of courses to return per page.
     * @param int $page The page number to retrieve.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator A paginated collection of Course models.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function findByKeyword(string $keyword, int $perPage, int $page)
    {
        $courses = Course::where('status', 1)->where('public', 1)->whereAny([
            'title',
            'description',
        ], 'LIKE', "%$keyword%");
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }

    /**
     * This method is used to find public and active courses that are not owned by the specified user.
     *
     * @param int $user_id The ID of the user whose courses should be excluded from the result.
     * @param int $perPage The number of courses to return per page.
     * @param int $page The page number to retrieve.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator A paginated collection of Course models that are public and active, excluding those owned by the specified user.
     *
     * @throws \Exception If any error occurs during the database query.
     */
    public function getPublicAndActiveCourses(int $user_id, int $perPage, int $page)
    {
        $courses = Course::where('user_id', '<>', $user_id)->where('status', 1)->where('public', 1);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
