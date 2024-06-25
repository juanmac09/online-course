<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Service\Auth\IAuth;
use App\Services\Auth\AuthService;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    public $authService;

    public function __construct(LoginRequest $request)
    {
        $authType = $request->get('auth_type', 1);

        if ($authType === 1) {
            $this->authService = App::make(AuthService::class);
            
        } else if($authType === 2) {
            $this->authService = App::make(GoogleAuthService::class);
        }
    }

    /**
     * Login a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function login(LoginRequest $request)
    {
        try {
            $token = $this->authService->login($request -> only('email', 'password'));
            if (!$token) {
                return response()->json(['success' => false, 'message' => 'Incorrect credentials'], 401);
            }
            return response()->json(['success' => true], 200, ['token' => $token]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
