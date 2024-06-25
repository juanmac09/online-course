<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICourseStatusReadRepository;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusReadService;

class CourseStatusReadService implements ICourseStatusReadService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICourseStatusReadRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    
    /**
     * Retrieves the course status for a given user and status.
     *
     * @param int $user_id The unique identifier of the user.
     * @param int $status The status of the course.
     *
     * @return mixed The course status information.
     */
    public function getCourseStatus(int $user_id, int $status,int $perPage, int $page)
    {
        return $this->courseRepository->getCourseStatus($user_id, $status, $perPage,  $page);
    }
}
