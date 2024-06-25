<?php

namespace App\Services\Role;

use App\Interfaces\Repository\Role\IManagingRoleUserWriteRepository;
use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Interfaces\Service\Role\IManagingRoleUserWriteService;

class ManagingRoleUserWriteService implements IManagingRoleUserWriteService
{
    public $userRepository;
    public $roleRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IManagingRoleUserWriteRepository $roleRepository, IUserProfileRepository $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * Change the role of a user.
     *
     * @param int $userId The ID of the user whose role is to be changed.
     * @param int $roleId The ID of the new role for the user.
     *
     * @return bool True if the role change was successful, false otherwise.
     */
    public function changeUserRole(int $userId, int $roleId)
    {
        $user = $this->userRepository->getUserForId($userId);
        return $this->roleRepository->changeUserRole($user, $roleId);
    }
}
