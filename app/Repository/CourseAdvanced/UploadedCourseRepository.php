<?php

namespace App\Repository\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\IUploadedCourseRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\User;

class UploadedCourseRepository implements IUploadedCourseRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }
    /**
     * Retrieves the uploaded courses of the given user.
     *
     * @param User $user The user whose uploaded courses are to be retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of the user's uploaded courses.
     */
    public function getUploadedCourses(User $user,int $perPage, int $page)
    {
        $courses = $user->authorCourse();
        return $this->paginationRepository->paginate($courses, $perPage, $page);
    }
}
