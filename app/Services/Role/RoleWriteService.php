<?php

namespace App\Services\Role;

use App\Interfaces\Repository\Role\IRoleWriteRepository;
use App\Interfaces\Service\Role\IRoleWriteService;
use App\Models\Role;

class RoleWriteService implements IRoleWriteService
{
    public $roleRepository;
    public function __construct(IRoleWriteRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Create a new role.
     *
     * @param array $roleData The data to create a new role.
     * @return Role The newly created role.
     */
    public function createRole(array $roleData)
    {
        $role = $this->roleRepository->createRole($roleData);
        return $role;
    }

    /**
     * Update an existing role.
     *
     * @param int $id The id of the role to be updated.
     * @param array $roleData The data to update the role.
     * @return Role The updated role.
     */
    public function updateRole(int $id, array $roleData)
    {
        $role = $this->roleRepository->updateRole($id, $roleData);
        return $role;
    }
}
