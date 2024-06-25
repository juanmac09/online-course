<?php

namespace App\Policies;

use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\User;


class CoursePolicy extends Policy
{
    public $courseOwnerService;
    public function __construct(ICourseOwnerService $courseOwnerService, IPermissionVerificationService $permissionService)
    {
        parent::__construct($permissionService);
        $this->courseOwnerService = $courseOwnerService;
    }

    /**
     * Checks whether the user has permission to perform an action or is the owner of a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     * @param string $permission The permission required to perform the action.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    private function checkPermissionOrOwnership(User $user, int $course_id, string $permission): bool
    {
        $permitted = $this->permissionService->hasPermission($user, $permission);
        $owner = $this->courseOwnerService->isOwner($user, $course_id);
        return $permitted || $owner;
    }


    /**
     * Determines whether the user has permission to view the index of courses.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function index(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'course.index');
        return $permitted;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Determines whether the user has permission to create a course.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function create(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'course.create');
        return $permitted;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be updated.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function update(User $user, int $course_id): bool
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'course.update');
    }
    /**
     * Determines whether the user has permission to disable a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be disabled.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function disable(User $user, int $course_id): bool
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'course.disable');
    }


    /**
     * Retrieves whether the user has permission to view their enrolled courses.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */

    public function getEnrolledCourses(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.enrrolled');
        return $permitted;
    }


    /**
     * Changes the active status of a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to have its active status changed.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function changeActiveStatusToCourse(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'courseAdvanced.change.status.active');
    }

    /**
     * Retrieves whether the user has permission to view their desactive courses.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function getDesactiveCourses(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.desactive');
        return $permitted;
    }

    /**
     * Changes the active status of a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to have its active status changed.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function makePrivateCourse(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'course.disable');
    }

    /**
     * Retrieves whether the user has permission to view their private courses.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     *
     * @throws \Exception If the user instance is not provided.
     */
    public function getPrivateCourses(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.private');
        return $permitted;
    }

    /**
     * Retrieves whether the user has permission to search courses by keyword.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function findByKeyword(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.search.keyword');
        return $permitted;
    }


    /**
     * Retrieves whether the user has permission to view their public and active courses.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function getPublicAndActiveCourses(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.active.public');
        return $permitted;
    }


    /**
     * Retrieves whether the user has permission to view their uploaded courses.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getCoursesUploaded(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.upload');
        return $permitted;
    }

    /**
     * Changes the active status of a course to public.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function makePublicACourse(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'courseAdvanced.make.public');
    }

    /**
     * Retrieves whether the user has permission to view their public courses.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     *
     * @throws \Exception If the user instance is not provided.
     */
    public function getPublicCourses(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'courseAdvanced.index.public');
        return $permitted;
    }


    /**
     * Determines whether the user has permission to enroll in a course.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions to enroll in a course, false otherwise.
     *
     * @throws \Exception If the user instance is not provided.
     */
    public function enrollInCourse(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'enrollments.create');
        return $permitted;
    }


    /**
     * Determines whether the user has permission to unenroll from a course.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions to unenroll from a course, false otherwise.
     */
    public function unEnrollInCourse(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'enrollments.delete');
        return $permitted;
    }


    /**
     * Determines whether the user has permission to search enrollments by keyword.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function findEnrollmentsByKeywords(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'enrollments.index.search.keyword');
        return $permitted;
    }


    /**
     * Retrieves whether the user has permission to view their public and active enrollments.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the necessary permissions, false otherwise.
     */
    public function getPublicAndActiveEnrollments(User $user)
    {
        $permitted = $this->permissionService->hasPermission($user, 'enrollments.index.active.public');
        return $permitted;
    }
}
