<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\Role\RoleWriteRequest;
use App\Interfaces\Service\Role\IRoleReadService;
use App\Interfaces\Service\Role\IRoleWriteService;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Documentation",
 *         version="1.0.0",
 *         description="API Documentation for the Online Course application",
 *         @OA\Contact(
 *             email="mateoromero0910@gmail.com",
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     )
 * )
 */

class RoleController extends Controller
{
    public $roleWriteService;
    public $roleReadService;

    public function __construct(IRoleWriteService $roleWriteService, IRoleReadService $roleReadService)
    {
        parent::__construct();
        $this->roleWriteService = $roleWriteService;
        $this->roleReadService = $roleReadService;
    }
    /**
     * Creates a new role.
     *
     * @param RoleWriteRequest $request The request object containing the role data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, role data, or error message.
     * @throws \Throwable If an error occurs while creating the role.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to create a role.
     * @OA\Post(
     *     path="/api/role/create",
     *     summary="Create a new role",
     *     description="This endpoint is used to create a new role.",
     *     operationId="createRole",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="role", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while creating the role",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function createRole(RoleWriteRequest $request)
    {
        Gate::authorize('create', Role::class);
        return $this->handleServiceCall(function () use ($request) {
            $role = $this->roleWriteService->createRole($request->only('name'));
            $this->cacheService->invalidateGroupCache('Role');
            return $role;
        });
    }


    /**
     * Gets all roles.
     *
     * @param CourseReadRequest $request The request object containing pagination data.
     * @return \Illuminate\Http\JsonResponse The response containing the list of roles.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to view roles.
     * @OA\Get(
     *     path="/api/role/all",
     *     summary="Get all roles",
     *     description="This endpoint retrieves all roles with pagination.",
     *     operationId="getAllRoles",
     *     tags={"Roles"},
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
     *         description="List of roles retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="roles", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Admin"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving roles",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getAllRoles(CourseReadRequest $request)
    {
        Gate::authorize('index', Role::class);
        return $this->handleServiceCall(function () use ($request) {
            $roles = $this->cacheService->storeInCache('Role', 'AllRoles', $request->perPage, $request->page, function () use ($request) {
                return $this->roleReadService->getAllRole($request->perPage, $request->page);
            }, 10);
            return $roles;
        });
    }

    /**
     * Updates an existing role.
     *
     * @param RoleWriteRequest $request The request object containing the role data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, updated role data, or error message.
     * @throws \Throwable If an error occurs while updating the role.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to update the role.
     * @OA\Put(
     *     path="/api/role/update",
     *     summary="Update an existing role",
     *     description="This endpoint updates an existing role.",
     *     operationId="updateRole",
     *     tags={"Roles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "name"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="role", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the role",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateRole(RoleWriteRequest $request)
    {
        Gate::authorize('update', Role::class);
        return $this->handleServiceCall(function () use ($request) {
            $role = $this->roleWriteService->updateRole($request->id, $request->only('name'));
            $this->cacheService->invalidateGroupCache('Role');
            return $role;
        });
    }
}
