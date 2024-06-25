<?php

namespace App\Interfaces\Repository\Role;

interface IRoleWriteRepository
{
    
    public function createRole(array $roleData);
    public function updateRole(int $id, array $roleData);
}
