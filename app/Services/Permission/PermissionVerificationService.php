<?php

namespace App\Services\Permission;

use App\Interfaces\Repository\Permission\IPermissionVerificationRepository;
use App\Interfaces\Service\Permission\IPermissionVerificationService;
use App\Models\User;

class PermissionVerificationService implements IPermissionVerificationService
{
    public $permissionRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IPermissionVerificationRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Checks if the user has the given permissions.
     *
     * @param User $user The user object to check permissions for.
     * @param string $permissions The permissions to check.
     *
     * @return bool True if the user has the permissions, false otherwise.
     */
    public function hasPermission(User $user, string $permissions)
    {
        $permission = $this->permissionRepository->hasPermission($permissions);
        $permitted = $permission->roles()->get()->find($user->role()->first()->id);
        return $permitted ? true : false;
    }
}
