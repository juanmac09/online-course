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


    /**
     * Authenticate user with login credentials.
     *
     * @param LoginRequest $request The request object containing email and password.
     * @return \Illuminate\Http\JsonResponse The response containing success status and authentication token.
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Authenticate user",
     *     description="Endpoint to authenticate user with email and password.",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password","auth_type"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Incorrect credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while authenticating",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->performLogin($request->get('auth_type', 1), $request->only('email', 'password'));
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The request object containing name, email, password, and role_id.
     * @return \Illuminate\Http\JsonResponse The response containing success status, user data, and authentication token.
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     description="Endpoint to register a new user.",
     *     operationId="register",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "role_id"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="role_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-27T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-27T12:00:00Z")
     *             ),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while registering user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
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
