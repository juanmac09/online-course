<?php

namespace App\Services\User;

use App\Interfaces\Repository\User\IUserReadRepository;
use App\Interfaces\Service\User\IUserReadService;

class UserReadService implements IUserReadService
{
    public $userRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IUserReadRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Get all users from the repository.
     *
     * @return array|null An array of users or null if no users are found.
     */
    public function getAllUsers(int $perPage, int $page)
    {
        $users = $this->userRepository -> getAllUsers( $perPage,  $page);
        return $users;
    }
}
