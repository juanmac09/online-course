<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICourseStatusWriteRepository;
use App\Models\Course;

class CourseStatusWriteRepository implements ICourseStatusWriteRepository
{

    /**
     * Changes the status of a course.
     *
     * @param int $course_id The ID of the course to update.
     * @param int $status The new status of the course.
     *
     * @return Course The updated course object.
     */
    public function changeCourseStatus(int $course_id, int $status)
    {
        $course = Course::find($course_id);
        $course->update([
            'status' => $status,
        ]);

        return $course;
    }
}
