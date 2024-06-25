<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyWriteRepository;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateWriteService;

class CoursePrivateWriteService implements ICoursePrivateWriteService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICoursePrivacyWriteRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Makes a course private with the given ID.
     *
     * @param int $courseId The ID of the course to be made private.
     *
     * @return bool True if the course was successfully made private, false otherwise.
     */
    public function makePrivateACourse(int $courseId)
    {
        return $this->courseRepository->changeCoursePrivacy($courseId,0);
    }
}
