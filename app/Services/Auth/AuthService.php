<?php

namespace App\Services\Auth;

use App\Interfaces\Service\Auth\IAuth;

class AuthService implements IAuth
{

    /**
     * Attempts to log the user in using the provided credentials.
     *
     * @param array $credentials The user's credentials, typically an associative array containing the username or email and password.
     *
     * @return bool Whether the login attempt was successful.
     */
    public function login(array $crendecials)
    {

        return auth()->attempt($crendecials);
    }
}
