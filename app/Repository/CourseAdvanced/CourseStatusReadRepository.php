<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICourseStatusReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Course;

class CourseStatusReadRepository implements ICourseStatusReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Retrieves the list of courses based on the user id and status.
     *
     * @param int $user_id The id of the user whose courses are to be retrieved.
     * @param int $status The status of the courses to be retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\ModelCollection A collection of Course models representing the courses that match the specified user id and status.
     */
    public function getCourseStatus(int $user_id, int $status,int $perPage, int $page)
    {
        $courses = Course::where('user_id', $user_id)->where('status', $status);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
