<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICourseStatusWriteRepository;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusWriteService;

class CourseStatusWriteService implements ICourseStatusWriteService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICourseStatusWriteRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Changes the status of a course.
     *
     * @param int $course_id The ID of the course to change the status for.
     * @param int $status The new status of the course.
     *
     * @return bool True if the status was successfully changed, false otherwise.
     */
    public function changeCourseStatus(int $course_id, int $status)
    {
        return $this->courseRepository->changeCourseStatus($course_id, $status);
    }
}
