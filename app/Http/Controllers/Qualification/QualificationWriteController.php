<?php

namespace App\Http\Controllers\Qualification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Qualification\QualificationCreateRequest;
use App\Interfaces\Service\Qualification\IQualificationWriteService;
use Illuminate\Support\Facades\Auth;
use App\Models\Qualification;
use Illuminate\Support\Facades\Gate;
class QualificationWriteController extends Controller
{
    public $qualificationService;

    public function __construct(IQualificationWriteService  $qualificationService)
    {
        parent::__construct();
        $this->qualificationService = $qualificationService;
    }

    /**
     * Creates a new qualification.
     *
     * @param QualificationCreateRequest $request The request object containing the qualification data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, qualification data, or error message.
     * @throws \Throwable If an error occurs while creating the qualification.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to create the qualification.
     * @OA\Post(
     *     path="/api/qualification/create",
     *     summary="Create a new qualification",
     *     description="This endpoint is used to create a new qualification.",
     *     operationId="createQualification",
     *    tags={"Qualifications"},
     *    security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qualification", "course_id"},
     *             @OA\Property(property="qualification", type="integer", example=5),
     *             @OA\Property(property="course_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Qualification created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="qualification", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="qualification", type="integer", example=90),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="course_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while creating the qualification",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function createQualification(QualificationCreateRequest $request)
    {
        Gate::authorize('create', [Qualification::class, $request->course_id]);
        return $this->handleServiceCall(function () use ($request) {
            $qualification =  $this->qualificationService->createQualification($request->qualification, Auth::user()->id, $request->course_id);
            $this->cacheService->invalidateGroupCache('Course');
            return $qualification;
        });
    }


    /**
     * Updates an existing qualification.
     *
     * @param QualificationCreateRequest $request The request object containing the qualification data.
     * @return \Illuminate\Http\JsonResponse The response containing the success status, updated qualification data, or error message.
     * @throws \Throwable If an error occurs while updating the qualification.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to update the qualification.
     * @OA\Put(
     *     path="/api/qualification/update",
     *     summary="Update an existing qualification",
     *     description="This endpoint updates an existing qualification.",
     *     operationId="updateQualification",
     *     tags={"Qualifications"},
     *      security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qualification", "course_id"},
     *             @OA\Property(property="qualification", type="integer", example=5),
     *             @OA\Property(property="course_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Qualification updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="qualification", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="qualification", type="integer", example=90),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="course_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the qualification",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateQualification(QualificationCreateRequest $request)
    {
        Gate::authorize('update', [Qualification::class, $request->course_id]);
        return $this->handleServiceCall(function () use ($request) {
            $qualification =  $this->qualificationService->updateQualification($request->qualification, Auth::user()->id, $request->course_id);
            $this->cacheService->invalidateGroupCache('Course');
            return $qualification;
        });
    }
}
