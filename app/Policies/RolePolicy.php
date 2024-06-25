<?php

namespace App\Policies;

use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\User;
use App\Traits\Authorization\GetPermissionTrait;

class RolePolicy extends Policy
{
    public function __construct( IPermissionVerificationService $permissionService)
    {
        parent::__construct($permissionService);
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'role.index');
        return $permitted;
    }

    /**
     * Determines whether the user can change the role of another user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function changeRoleToUser(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'role.assign');
        return $permitted;
    }

    /**
     * Determine whether the user can create a new role.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'role.create');
        return $permitted;
    }

    /**
     * Determine whether the user can update a role.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user): bool
    {
        $permitted = $this->permissionService->hasPermission($user, 'role.update');
        return $permitted;
    }
}
