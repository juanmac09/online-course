<?php

namespace App\Interfaces\Service\Permission;

use App\Models\User;

interface IPermissionVerificationService
{
    public function hasPermission(User $user,string $permissions);
}
