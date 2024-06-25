<?php

namespace App\Services\Auth;

use App\Interfaces\Service\Auth\IAuth;
use Google\Client;

class GoogleAuthService implements IAuth
{
    public function login(array $crendecials)
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope('email');
        $client->addScope('profile');

        // Redireccionar al usuario a Google para autenticación
        if (!isset($credentials['code'])) {
            return $client->createAuthUrl();
        }
    }
}
