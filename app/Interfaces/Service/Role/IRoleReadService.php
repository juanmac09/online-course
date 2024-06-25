<?php

namespace App\Interfaces\Service\Role;

interface IRoleReadService
{
    public function getAllRole(int $perPage, int $page);
}
