<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\IUploadedCourseRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Service\CourseAdvanced\IUploadedCourseService;
use App\Models\User;

class UploadedCourseService implements IUploadedCourseService
{
    public $courseRepository;
    public $userRepository;



    public function __construct(IUploadedCourseRepository $courseRepository, IUserProfileRepository $userRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * Retrieves the uploaded courses for a given user.
     *
     * @param User|null $user The user object. If not provided, the method will attempt to retrieve the user using the provided user_id.
     * @param int|null $user_id The user's ID. If provided, the method will attempt to retrieve the user using this ID.
     *
     * @return array An array of uploaded courses for the specified user.
     */
    public function getUploadedCourses(?User $user, ?int $user_id,int $perPage, int $page)
    {
        $user = (isset($user)) ? $user : $this->userRepository->getUserForId($user_id);

        return $this->courseRepository->getUploadedCourses($user, $perPage,  $page);
    }
}
