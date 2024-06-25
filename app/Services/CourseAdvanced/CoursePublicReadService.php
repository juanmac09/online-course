<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyReadRepository;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicReadService;

class CoursePublicReadService implements ICoursePublicReadService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICoursePrivacyReadRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }


    /**
     * Retrieves the public courses for a given user.
     *
     * @param int $user_id The ID of the user whose public courses should be retrieved.
     *
     * @return array An array of public courses for the specified user.
     */
    public function getPublicCourses(int $user_id,int $perPage, int $page)
    {
        return $this->courseRepository->getCoursePrivacy($user_id, 1, $perPage,  $page);
    }
}
