<?php

namespace App\Interfaces\Service\Role;

interface IManagingRoleUserWriteService
{
    public function changeUserRole(int $userId, int $roleId);
}
