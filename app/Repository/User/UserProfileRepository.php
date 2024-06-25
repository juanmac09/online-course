<?php

namespace App\Repository\User;

use App\Interfaces\Repository\User\IUserProfileRepository;
use App\Models\User;

class UserProfileRepository implements IUserProfileRepository
{
    /**
     * Retrieves a user by their ID.
     *
     * @param int $userId The unique identifier of the user.
     *
     * @return User|null The user object if found, or null if not found.
     */
    public function getUserForId(int $userId)
    {
        $user = User::find($userId);
        return $user;
    }
}
