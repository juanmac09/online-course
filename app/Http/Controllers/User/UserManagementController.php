<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\User\UserWriteRequest;
use App\Interfaces\Service\User\IUserReadService;
use App\Interfaces\Service\User\IUserWriteService;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserManagementController extends Controller
{
    public $userWriteService;
    public $userReadService;
    public function __construct(IUserWriteService $userWriteService, IUserReadService $userReadService)
    {
        parent::__construct();
        $this->userWriteService = $userWriteService;
        $this->userReadService = $userReadService;
    }
    /**
     * Creates a new user.
     *
     * @param UserWriteRequest $request The request object containing the user data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, user data, or error message.
     * @throws \Throwable If an error occurs while creating the user.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to create a user.
     * @OA\Post(
     *     path="/api/user/create",
     *     summary="Create a new user",
     *     description="This endpoint is used to create a new user.",
     *     operationId="createUser",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "role_id"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="role_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while creating the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function createUser(UserWriteRequest $request)
    {
        Gate::authorize('create', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->createUser($request->only('name', 'email', 'role_id'));
            $this->cacheService->invalidateGroupCache('User');
            return $user;
        });
    }

    /**
     * Gets all users.
     *
     * @param CourseReadRequest $request The request object containing pagination data.
     * @return \Illuminate\Http\JsonResponse The response containing the list of users.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to view users.
     * @OA\Get(
     *     path="/api/user/all",
     *     summary="Get all users",
     *     description="This endpoint retrieves all users with pagination.",
     *     operationId="getAllUser",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of items per page",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of users retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="users", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="role_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving users",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getAllUser(CourseReadRequest $request)
    {
        Gate::authorize('getAllUser', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $users = $this->cacheService->storeInCache('User', 'allUser', $request->perPage, $request->page, function () use ($request) {
                return $this->userReadService->getAllUsers($request->perPage, $request->page);
            }, 10);
            return $users;
        });
    }


    /**
     * Updates an existing user.
     *
     * @param UserWriteRequest $request The request object containing the user data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, updated user data, or error message.
     * @throws \Throwable If an error occurs while updating the user.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to update the user.
     * @OA\Put(
     *     path="/api/user/update",
     *     summary="Update an existing user",
     *     description="This endpoint updates an existing user.",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "name", "email", "role_id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateUser(UserWriteRequest $request)
    {
        Gate::authorize('update', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->updateUser($request->id, $request->only('name', 'email', 'role_id'));
            $this->cacheService->invalidateGroupCache('User');
            return $user;
        });
    }


    /**
     * Disables an existing user.
     *
     * @param UserWriteRequest $request The request object containing the user ID.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, disabled user data, or error message.
     * @throws \Throwable If an error occurs while disabling the user.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to disable the user.
     * @OA\Put(
     *     path="/api/user/disable",
     *     summary="Disable an existing user",
     *     description="This endpoint disables an existing user.",
     *     operationId="disableUser",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User disabled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="role_id", type="integer", example=2),
     *                 @OA\Property(property="disabled_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while disabling the user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function disableUser(UserWriteRequest $request)
    {
        Gate::authorize('disable', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->disableUser($request->id);
            $this->cacheService->invalidateGroupCache('User');
            return $user;
        });
    }
}
