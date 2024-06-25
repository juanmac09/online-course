<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\IAdvancedCourseRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\User;

class AdvancedCourseRepository implements IAdvancedCourseRepository
{

    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Get enrolled courses for a specific user.
     *
     * @param User $user The user for which to retrieve enrolled courses.
     * @param int $id The unique identifier of the user. (Not used in this method, but included for consistency with the interface method signature.)
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of enrolled courses.
     *
     * @throws \Exception If an error occurs while retrieving the courses.
     */
    public function getEnrolledCourses(User $user,int $perPage, int $page)
    {
        $courses = $user->courses()->where('status', 1)->where('public', 1);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
