<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Factories\AuthServiceFactory;
use App\Interfaces\Service\Auth\IRegisterService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $registerAuthService;
    protected $authService;

    public function __construct(IRegisterService $registerAuthService)
    {
        $this->registerAuthService = $registerAuthService;
    }

    public function login(LoginRequest $request)
    {
        return $this->performLogin($request->get('auth_type', 1), $request->only('email', 'password'));
    }

    
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->registerAuthService->registerUser($request->only('name', 'email', 'password', 'role_id'));
            if ($user) {
                $loginResponse = $this->performLogin(1, $request->only('email', 'password'));
                $token = $loginResponse->headers->get('token');

                return response()->json(['success' => true, 'user' => $user], 200, ['token' => $token]);
            }

            return response()->json(['success' => false, 'message' => 'Error during registration'], 500);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }


    protected function performLogin(int $authType, array $credentials)
    {
        $this->authService = AuthServiceFactory::create($authType);

        try {
            $token = $this->authService->login($credentials);
            if (!$token) {
                return response()->json(['success' => false, 'message' => 'Incorrect credentials'], 401);
            }
            return response()->json(['success' => true], 200, ['token' => $token]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
