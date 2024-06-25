<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyWriteRepository;
use App\Models\Course;

class CoursePrivacyWriteRepository implements ICoursePrivacyWriteRepository
{
    /**
     * Changes the privacy of a course.
     *
     * @param int $course_id The ID of the course to change the privacy for.
     * @param int $privacy The new privacy setting for the course.
     *
     * @return Course The updated course object.
     */
    public function changeCoursePrivacy(int $course_id, int $privacy)
    {
        $course = Course::find($course_id);
        $course->update([
            'public' =>  $privacy,
        ]);
        return $course;
    }
}
