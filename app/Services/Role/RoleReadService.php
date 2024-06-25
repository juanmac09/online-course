<?php

namespace App\Services\Role;

use App\Interfaces\Repository\Role\IRoleReadRepository;
use App\Interfaces\Service\Role\IRoleReadService;

class RoleReadService implements IRoleReadService
{
    public $roleRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IRoleReadRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * This method retrieves all roles from the repository.
     *
     * @return mixed Returns a collection of roles.
     *
     * @throws \Exception If there is an error retrieving the roles.
     */
    public function getAllRole(int $perPage, int $page)
    {
        $roles = $this->roleRepository->getAllRole($perPage, $page);
        return $roles;
    }
}
