<?php

namespace App\Services\CourseAdvanced;

use App\Interfaces\Repository\CourseAdvanced\IAdvancedCourseRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Service\CourseAdvanced\IAdvancedCourseService;
use App\Models\User;

class AdvancedCourseService implements IAdvancedCourseService
{
    public $courseRespository;
    public $userRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IAdvancedCourseRepository $courseRespository, IUserProfileRepository $userRepository)
    {
        $this->courseRespository = $courseRespository;
        $this->userRepository = $userRepository;
    }

    
    /**
     * Get the enrolled courses for a user.
     *
     * @param User|null $user The user object. If not provided, the method will retrieve the user using the provided user_id.
     * @param int|null $user_id The user's ID. If provided, the method will retrieve the user using this ID.
     * @param int $id The course ID.
     *
     * @return array An array of enrolled courses for the user.
     */
    public function getEnrolledCourses(?User $user, ?int $user_id,int $perPage, int $page)
    {
        $user = (isset($user)) ? $user : $this->userRepository->getUserForId($user_id);
        $courses = $this->courseRespository->getEnrolledCourses($user, $perPage,  $page);
        return $courses;
    }
}
