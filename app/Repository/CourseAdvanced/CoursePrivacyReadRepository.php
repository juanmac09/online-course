<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\ICoursePrivacyReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Course;

class CoursePrivacyReadRepository implements ICoursePrivacyReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }
    /**
     * Get the courses based on user id and privacy level.
     *
     * @param int $user_id The id of the user whose courses should be retrieved.
     * @param int $privacy The privacy level of the courses to be retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection A collection of Course models that match the specified user id and privacy level.
     */
    public function getCoursePrivacy(int $user_id, int $privacy,int $perPage, int $page)
    {
        $courses  = Course::where('user_id', $user_id)->where('public', $privacy)->where('status', 1);
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
