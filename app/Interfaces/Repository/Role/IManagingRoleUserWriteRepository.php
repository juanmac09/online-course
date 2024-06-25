<?php

namespace App\Interfaces\Repository\Role;

use App\Models\User;

interface IManagingRoleUserWriteRepository
{
    public function changeUserRole(User $user, int $role_id);
}
