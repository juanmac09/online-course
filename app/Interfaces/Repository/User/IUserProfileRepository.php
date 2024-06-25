<?php

namespace App\Interfaces\Repository\User;

interface IUserProfileRepository
{
    public function getUserForId(int $userId);
}
