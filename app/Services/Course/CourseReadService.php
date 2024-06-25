<?php

namespace App\Services\Course;

use App\Interfaces\Repository\Course\ICourseReadRepository;
use App\Interfaces\Service\Course\ICourseReadService;

class CourseReadService implements ICourseReadService
{

    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICourseReadRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    
    /**
     * Retrieves all courses from the repository.
     *
     * @return array An array of all courses.
     */
    public function getAllCourse(int $perPage, int $page)
    {
        $courses = $this->courseRepository->getAllCourse($perPage, $page);
        return $courses;
    }
}
