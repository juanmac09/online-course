<?php

namespace App\Traits\Course;

use Illuminate\Pagination\LengthAwarePaginator;

trait AttachQualificationsTrait
{
    /**
     * Attaches qualifications to the given courses.
     *
     * @param LengthAwarePaginator $courses The paginated collection of courses.
     * @param \App\Services\QualificationService $qualificationService The service responsible for fetching qualifications.
     *
     * @return LengthAwarePaginator The updated paginated collection of courses with attached qualifications.
     */
    private function attachQualificationsToCourses(LengthAwarePaginator $courses, $qualificationService): LengthAwarePaginator
    {
        foreach ($courses as $course) {
            $course['qualification'] = $qualificationService->getAllQualificationsByCourse($course['id']);
        }
        return $courses;
    }
}
