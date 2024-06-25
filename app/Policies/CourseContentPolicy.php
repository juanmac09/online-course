<?php

namespace App\Policies;

use App\Interfaces\Repository\Content\IContentRepository;
use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\CourseContent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CourseContentPolicy extends Policy
{
    public $courseOwnerService;
    public $contentRepository;
    public function __construct(ICourseOwnerService $courseOwnerService, IPermissionVerificationService $permissionService, IContentRepository $contentRepository)
    {
        parent::__construct($permissionService);
        $this->courseOwnerService = $courseOwnerService;
        $this->contentRepository = $contentRepository;
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
     * Checks whether the user has permission to perform an action or is the owner of a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     * @param string $permission The permission required to perform the action.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getContentForCourse(User $user, int $course_id): bool
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'content.index.course');
    }

    /**
     * Determines whether the user can create a new course content.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function create(User $user, int $course_id): bool
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'content.upload');
    }

    /**
     * Determines whether the user can update a course content.
     *
     * @param User $user The authenticated user instance.
     * @param int $content_id The ID of the content to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function update(User $user, int $content_id): bool
    {
        $content = $this->contentRepository->contentById($content_id);
        return $this->checkPermissionOrOwnership($user, $content->course_id, 'content.update');
    }

    /**
     * Determines whether the user can disable a course content.
     *
     * @param User $user The authenticated user instance.
     * @param int $content_id The ID of the content to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function disable(User $user, int $content_id): bool
    {
        $content = $this->contentRepository->contentById($content_id);
        return $this->checkPermissionOrOwnership($user, $content->course_id, 'content.disable');
    }

    /**
     * Determines whether the user can update the order of a course content.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function updateOrder(User $user, int $course_id): bool
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'content.update.order');
    }



    /**
     * Checks whether the user has permission to view the status of desactivated content for a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getStatusDesactiveContent(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'contentAdvanced.index.desactive');
    }


    /**
     * Changes the status of a content from desactivated to active.
     *
     * @param User $user The authenticated user instance.
     * @param int $content_id The ID of the content to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function changeStatusActiveContent(User $user, int $content_id)
    {
        $content = $this->contentRepository->contentById($content_id);
        return $this->checkPermissionOrOwnership($user, $content->course_id, 'contentAdvanced.make.active');
    }

    /**
     * Checks whether the user has permission to view the status of private content for a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getPrivateContent(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'contentAdvanced.index.private');
    }

    /**
     * Makes a content private.
     *
     * @param User $user The authenticated user instance.
     * @param int $content_id The ID of the content to be made private.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function makePrivateAContent(User $user, int $content_id)
    {
        $content = $this->contentRepository->contentById($content_id);
        return $this->checkPermissionOrOwnership($user, $content->course_id, 'contentAdvanced.make.private');
    }


    /**
     * Checks whether the user has permission to view the public and active content for a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getPublicAndActiveContent(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'contentAdvanced.index.public.active') || $this->courseOwnerService->isSuscriber($user, $course_id);
    }

    /**
     * Checks whether the user has permission to view the public content for a course.
     *
     * @param User $user The authenticated user instance.
     * @param int $course_id The ID of the course to be checked.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function getPublicContent(User $user, int $course_id)
    {
        return $this->checkPermissionOrOwnership($user, $course_id, 'contentAdvanced.index.public');
    }

    
    /**
     * Makes a content public.
     *
     * @param User $user The authenticated user instance.
     * @param int $content_id The ID of the content to be made public.
     *
     * @return bool True if the user has the necessary permissions or owns the course, false otherwise.
     */
    public function makePublicAContent(User $user, int $content_id)
    {
        $content = $this->contentRepository->contentById($content_id);
        return $this->checkPermissionOrOwnership($user, $content->course_id, 'contentAdvanced.make.public');
    }
}
