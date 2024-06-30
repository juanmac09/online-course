<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\ManagingRoleUserRequest;
use App\Interfaces\Service\Role\IManagingRoleUserWriteService;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class ManagingRoleUserController extends Controller
{
    public $roleService;

    public function __construct(IManagingRoleUserWriteService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
    }


    /**
     * Changes the role of a user.
     *
     * @param ManagingRoleUserRequest $request The request object containing the user ID and the new role ID.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, updated user data, or error message.
     * @throws \Throwable If an error occurs while changing the user's role.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to change the role.
     * @OA\Put(
     *     path="/api/role/change-user",
     *     summary="Change role of a user",
     *     description="This endpoint is used to change the role of a user.",
     *     operationId="changeRoleToUser",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "role_id"},
     *             @OA\Property(property="id", type="integer", example=1, description="User ID"),
     *             @OA\Property(property="role_id", type="integer", example=2, description="New Role ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User role changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="role", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="User")
     *                 ),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while changing the user's role",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function changeRoleToUser(ManagingRoleUserRequest $request)
    {
        Gate::authorize('changeRoleToUser', Role::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->roleService->changeUserRole($request->id, $request->role_id);
            $this->cacheService->invalidateGroupCache('User');
            return $user;
        });
    }
}
