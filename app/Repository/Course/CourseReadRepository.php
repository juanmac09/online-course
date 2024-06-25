<?php

namespace App\Repository\Course;

use App\Interfaces\Repository\Course\ICourseReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Course;

class CourseReadRepository implements ICourseReadRepository
{

    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Retrieves all active courses.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] A collection of active courses.
     */
    public function getAllCourse(int $perPage, int $page)
    {
        $courses = Course::where('status', 1);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
