<?php

namespace App\Services\User;

use App\Interfaces\Repository\User\IUserWriteRepository;
use App\Interfaces\Service\User\IUserWriteService;

class UserWriteService implements IUserWriteService
{
    public $userRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IUserWriteRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Create a new user.
     *
     * @param array $userData An associative array containing user data.
     * @return object The newly created user object.
     *
     * @throws Exception If the user creation fails.
     */
    public function createUser(array $userData)
    {
        $userData['password'] = $userData['email'];
        $user = $this->userRepository->createUser($userData);
        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param int $id The unique identifier of the user to be updated.
     * @param array $userData An associative array containing the updated user data.
     * @return object The updated user object.
     *
     * @throws Exception If the user update fails.
     */
    public function updateUser(int $id, array $userData)
    {
        $user = $this->userRepository->updateUser($id, $userData);
        return $user;
    }

    /**
     * Disable a user.
     *
     * @param int $id The unique identifier of the user to be disabled.
     * @return object The disabled user object.
     *
     * @throws Exception If the user disable fails.
     */
    public function disableUser(int $id)
    {
        $user = $this->userRepository->disableUser($id);
        return $user;
    }
}
