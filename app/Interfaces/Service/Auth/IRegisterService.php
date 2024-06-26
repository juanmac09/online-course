<?php

namespace App\Interfaces\Service\Auth;

interface IRegisterService
{
    public function registerUser(array $userData);
}
