<?php

namespace App\Interfaces\Service\Role;

interface IRoleWriteService
{
    public function createRole(array $roleData);
    public function updateRole(int $id, array $roleData);
}
