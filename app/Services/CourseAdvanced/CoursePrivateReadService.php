<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyReadRepository;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateReadService;

class CoursePrivateReadService implements ICoursePrivateReadService
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
     * Retrieves the private courses of a user.
     *
     * @param int $user_id The ID of the user whose private courses should be retrieved.
     *
     * @return array An array of private courses belonging to the specified user.
     */
    public function getPrivateCourses(int $user_id,int $perPage, int $page)
    {
        return $this->courseRepository->getCoursePrivacy($user_id, 0, $perPage,  $page);
    }
}
