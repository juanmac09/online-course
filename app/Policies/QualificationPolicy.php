<?php

namespace App\Policies;

use App\Interfaces\Service\Course\ICourseOwnerService;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\Qualification;
use App\Models\User;

class QualificationPolicy extends Policy
{
    public $courseOwnerService;
    public function __construct(ICourseOwnerService $courseOwnerService, IPermissionVerificationService $permissionService)
    {
        parent::__construct($permissionService);
        $this->courseOwnerService = $courseOwnerService;
    }



    /**
     * Determines if the user has the necessary permissions and subscription to create a new qualification.
     *
     * @param User $user The authenticated user.
     * @param int $course_id The ID of the course the user wants to create a qualification for.
     *
     * @return bool True if the user has the necessary permissions and subscription, false otherwise.
     */
    public function create(User $user, int $course_id): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'qualification.create');
        $isSuscribe = $this->courseOwnerService->isSuscriber($user, $course_id);
        return $permitted && $isSuscribe;
    }

    /**
     * Determines if the user has the necessary permissions and subscription to update a qualification.
     *
     * @param User $user The authenticated user.
     * @param int $course_id The ID of the course the user wants to update a qualification for.
     *
     * @return bool True if the user has the necessary permissions and subscription, false otherwise.
     */
    public function update(User $user, int $course_id): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'qualification.update');
        $isSuscribe = $this->courseOwnerService->isSuscriber($user, $course_id);
        return $permitted && $isSuscribe;
    }
}
