<?php

namespace App\Repository\Course;

use App\Interfaces\Repository\Course\ICourseRepository;
use App\Models\Course;

class CourseRepository implements ICourseRepository
{
    /**
     * Retrieves a course by its ID.
     *
     * @param int $courseId The ID of the course to retrieve.
     *
     * @return Course|null The course object if found, or null if not found.
     */
    public function courseById(int $courseId)
    {
        $course = Course::find($courseId);
        return $course;
    }
}
