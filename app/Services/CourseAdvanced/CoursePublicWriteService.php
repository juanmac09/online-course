<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyWriteRepository;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicWriteService;

class CoursePublicWriteService implements ICoursePublicWriteService
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
     * Makes a course public with the given ID.
     *
     * @param int $courseId The ID of the course to be made public.
     *
     * @return bool True if the course was successfully made public, false otherwise.
     */
    public function makePublicACourse(int $courseId)
    {
        return $this->courseRepository->changeCoursePrivacy($courseId,1);
    }
}
