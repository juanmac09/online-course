<?php

namespace App\Repository\Role;

use App\Interfaces\Repository\Role\IManagingRoleUserWriteRepository;
use App\Models\User;

class ManagingRoleUserWriteRepository implements IManagingRoleUserWriteRepository
{

    /**
     * This function is responsible for changing the role of a user.
     *
     * @param User $user The user whose role needs to be changed.
     * @param int $role_id The ID of the new role.
     *
     * @return User The updated user model with the new role.
     *
     * @throws \Exception If the user model cannot be updated.
     */
    public function changeUserRole(User $user, int $role_id)
    {
        $user->update([
            'role_id' => $role_id,
        ]);
        return $user;
    }
}
