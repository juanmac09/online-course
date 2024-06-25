<?php

namespace App\Repository\User;

use App\Interfaces\Repository\User\IUserWriteRepository;
use App\Models\User;

class UserWriteRepository implements IUserWriteRepository
{
    /**
     * Create a new user.
     *
     * @param array $userData The data to create a new user with.
     *
     * @return User The newly created user instance.
     */
    public function createUser(array $userData)
    {
        return User::create($userData);
    }

    /**
     * Update an existing user.
     *
     * @param int $id The ID of the user to update.
     * @param array $userData The data to update the user with.
     *
     * @return User The updated user instance.
     */
    public function updateUser(int $id, array $userData)
    {
        $user = User::find($id);
        $user->update($userData);
        return $user;
    }

    
    /**
     * Disable a user by updating their status to 0.
     *
     * @param int $id The ID of the user to disable.
     *
     * @return User The updated user instance with status set to 0.
     */
    public function disableUser(int $id)
    {
        $user = User::find($id);
        $user->update([
            'status' => 0
        ]);
        return $user;
    }
}
