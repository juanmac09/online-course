<?php

namespace App\Repository\Role;

use App\Interfaces\Repository\Role\IRoleReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\Role;

class RoleReadRepository implements IRoleReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getAllRole(int $perPage, int $page)
    {
        $roles =  Role::query();
        return $this->paginationRepository->paginate($roles, $perPage, $page);
    }
}
