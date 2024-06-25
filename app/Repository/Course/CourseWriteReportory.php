<?php

namespace App\Repository\Course;

use App\Interfaces\Repository\Course\ICourseWriteReportory;
use App\Models\Course;

class CourseWriteReportory implements ICourseWriteReportory
{
    /**
     * Create a new course.
     *
     * @param array $courseData The data to create a new course with.
     * @return Course The newly created course instance.
     */
    public function createCourse(array $courseData)
    {
        $course = Course::create($courseData);
        return $course;
    }

    /**
     * Update an existing course.
     *
     * @param int $id The ID of the course to be updated.
     * @param array $courseData The data to update the course with.
     * @return Course The updated course instance.
     */
    public function updateCourse(int $id, array $courseData)
    {
        $course = Course::find($id);
        $course->update($courseData);
        return $course;
    }
    /**
     * Disable a course.
     *
     * This method updates the status of a course to '0', effectively disabling it.
     *
     * @param int $id The ID of the course to be disabled.
     *
     * @return Course The updated course instance.
     */
    public function disableCourse(int $id)
    {
        $course = Course::find($id);
        $course->update([
            'status' => 0,
        ]);
        return $course;
    }
}
