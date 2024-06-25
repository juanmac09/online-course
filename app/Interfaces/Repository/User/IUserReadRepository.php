<?php

namespace App\Interfaces\Repository\User;

interface IUserReadRepository
{
    public function getAllUsers(int $perPage, int $page);
}
