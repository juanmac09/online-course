<?php

namespace App\Services\Auth;

use App\Interfaces\Repository\User\IUserWriteRepository;
use App\Interfaces\Service\Auth\IRegisterService;

class RegisterService implements IRegisterService
{
    public $userRepository;


    public function __construct(IUserWriteRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }



    /**
     * Registers a new user using the provided user data.
     *
     * @param array $userData An associative array containing the user's data, typically including fields such as name, email, password, and any other relevant information.
     *
     * @return \App\Models\User|null The newly created user instance, or null if the registration fails.
     */
    public function registerUser(array $userData)
    {
        $user = $this->userRepository->createUser($userData);
        return $user;
    }
}
