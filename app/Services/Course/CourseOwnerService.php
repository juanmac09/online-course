<?php

namespace App\Services\Course;

use App\Interfaces\Repository\Course\ICourseRepository;
use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Models\User;

class CourseOwnerService implements ICourseOwnerService
{
    public $courseRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ICourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Checks if the given user is the owner of the specified course.
     *
     * @param User $user The user to check ownership for.
     * @param int $courseId The ID of the course to check ownership for.
     *
     * @return bool True if the user is the owner of the course, false otherwise.
     */
    public function isOwner(User $user, int $courseId)
    {
        $course = $this->courseRepository->courseById($courseId);
        return ($course->user_id == $user->id) ? true : false;
    }

    /**
     * Checks if the given user is a subscriber of the specified course.
     *
     * @param User $user The user to check subscription for.
     * @param int $courseId The ID of the course to check subscription for.
     *
     * @return bool True if the user is a subscriber of the course, false otherwise.
     */
    public function isSuscriber(User $user, int $courseId)
    {
        return $user->courses()->where('courses.id', $courseId)->exists();
    }
}
