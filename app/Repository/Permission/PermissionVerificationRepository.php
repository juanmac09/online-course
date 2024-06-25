<?php

namespace App\Repository\Permission;

use App\Interfaces\Repository\Permission\IPermissionVerificationRepository;
use App\Models\Permissions;
use App\Models\User;

class PermissionVerificationRepository implements IPermissionVerificationRepository
{
    /**
     * Checks if the user has the specified permission.
     *
     * @param string $permission The name of the permission to check.
     *
     * @return mixed|null The Permissions model if the permission exists, otherwise null.
     */
    public function hasPermission(string $permission)
    {
        $permission = Permissions::where('permission', $permission)->first();
        return $permission;
    }
}
