<?php

namespace App\Repository\Enrollments;

use App\Interfaces\Repository\Enrollments\IEnrollmentsWriteRespository;
use App\Models\Course;

class EnrollmentsWriteRespository implements IEnrollmentsWriteRespository
{
    /**
     * Enrolls a user in a course.
     *
     * @param int $user_id The ID of the user to be enrolled.
     * @param int $courseId The ID of the course to be enrolled in.
     *
     * @return bool Whether the enrollment was successful.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the course with the given ID is not found.
     */
    public function enrollInCourse(int  $user_id, int $courseId)
    {

        $course = Course::find($courseId);
        return $course->users()->sync($user_id);
    }

    /**
     * Unenrolls a user from a course.
     *
     * @param int $user_id The ID of the user to be unenrolled.
     * @param int $courseId The ID of the course to be unenrolled from.
     *
     * @return bool Whether the unenrollment was successful.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the course with the given ID is not found.
     */
    public function unEnrollInCourse(int $user_id, int $courseId)
    {
        $course = Course::find($courseId);
        return $course->users()->detach($user_id);
    }
}
