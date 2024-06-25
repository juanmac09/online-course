<?php

namespace App\Interfaces\Service\User;

interface IUserWriteService
{
    public function createUser(array $userData);
    public function updateUser(int $id,array $userData);
    public function disableUser(int $id);
}
