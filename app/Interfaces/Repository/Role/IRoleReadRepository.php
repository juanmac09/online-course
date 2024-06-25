<?php

namespace App\Interfaces\Repository\Role;

interface IRoleReadRepository
{
    public function getAllRole(int $perPage, int $page);
}
