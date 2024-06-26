<?php

namespace App\Factories;

use App\Interfaces\Service\Auth\IAuth;
use App\Services\Auth\AuthService;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Support\Facades\App;

class AuthServiceFactory
{
    /**
     * Creates an instance of the specified auth service.
     *
     * @param int $authType The type of the auth service to create.
     * @return IAuth An instance of the specified auth service.
     */
    public static function create(int $authType): IAuth
    {
        switch ($authType) {
            case 2:
                return App::make(GoogleAuthService::class);
            case 1:
            default:
                return App::make(AuthService::class);
        }
    }
}
