<?php

namespace App\Services\Course;

use App\Interfaces\Repository\Course\ICourseWriteReportory;
use App\Interfaces\Service\Course\ICourseWriteService;

class CourseWriteService implements ICourseWriteService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICourseWriteReportory $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    
    /**
     * Create a new course.
     *
     * @param array $courseData The data for the new course.
     * @param int $user_id The unique identifier of the user who owns the course.
     * @return Course The newly created course object.
     */
    public function createCourse(array $courseData, int $user_id)
    {
        $courseData['user_id'] = $user_id;
        $course = $this->courseRepository->createCourse($courseData);
        return $course;
    }


    /**
     * Update an existing course.
     *
     * @param int $id The unique identifier of the course to be updated.
     * @param array $courseData The updated data for the course.
     * @return Course The updated course object.
     */
    public function updateCourse(int $id, array $courseData)
    {
        $course = $this->courseRepository->updateCourse($id, $courseData);
        return $course;
    }


    /**
     * Disable an existing course.
     *
     * @param int $id The unique identifier of the course to be disabled.
     * @return Course The disabled course object.
     */
    public function disableCourse(int $id)
    {
        $course = $this->courseRepository->disableCourse($id);
        return $course;
    }
}
