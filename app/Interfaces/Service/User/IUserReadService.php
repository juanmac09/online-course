<?php

namespace App\Interfaces\Service\User;

interface IUserReadService
{
    public function getAllUsers(int $perPage, int $page);
}
