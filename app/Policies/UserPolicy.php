<?php

namespace App\Policies;

use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy extends Policy
{

    public function __construct(IPermissionVerificationService $permissionService)
    {
        parent::__construct($permissionService);
    }





    /**
     * Determines whether the user has the permission to create a new user.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the permission, false otherwise.
     */
    public function create(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'user.create');
        return $permitted;
    }


    /**
     * Determines whether the user has the permission to update an existing user.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the permission, false otherwise.
     */
    public function update(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'user.update');
        return $permitted;
    }

    /**
     * Determine whether the user can delete the model.
     */
    /**
     * Determine whether the user can disable the model.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the permission, false otherwise.
     */
    public function disable(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'user.disable');
        return $permitted;
    }


    /**
     * Determines whether the user has the permission to get all users.
     *
     * @param User $user The authenticated user instance.
     *
     * @return bool True if the user has the permission, false otherwise.
     */
    public function getAllUser(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'user.index');
        return $permitted;
    }
}
