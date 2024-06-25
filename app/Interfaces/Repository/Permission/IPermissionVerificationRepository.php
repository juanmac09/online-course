<?php

namespace App\Interfaces\Repository\Permission;

use App\Models\User;

interface IPermissionVerificationRepository
{
    public function hasPermission(string $permission);
}
