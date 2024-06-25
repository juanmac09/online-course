<?php

namespace App\Repository\Role;

use App\Interfaces\Repository\Role\IRoleWriteRepository;
use App\Models\Role;

class RoleWriteRepository implements IRoleWriteRepository
{
    /**
     * This function creates a new role in the database.
     *
     * @param array $roleData An associative array containing the role data.
     *                       The keys should match the column names in the 'roles' table.
     *
     * @return \App\Models\Role The newly created Role model instance.
     *
     * @throws \Exception If there is an error creating the role.
     */
    public function createRole(array $roleData)
    {
        $role = Role::create($roleData);
        return $role;
    }

    /**
     * Updates an existing role in the database.
     *
     * @param int $id The unique identifier of the role to be updated.
     * @param array $roleData An associative array containing the updated role data.
     *                       The keys should match the column names in the 'roles' table.
     *
     * @return \App\Models\Role The updated Role model instance.
     *
     * @throws \Exception If there is an error updating the role.
     */
    public function updateRole(int $id, array $roleData)
    {
        $role = Role::find($id);
        $role->update($roleData);
        return $role;
    }
}
