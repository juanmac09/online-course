<?php

namespace App\Interfaces\Repository\User;

interface IUserWriteRepository
{
    public function createUser(array $userData);
    public function updateUser(int $id,array $userData);
    public function disableUser(int $id);
}
