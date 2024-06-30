<?php

namespace App\Http\Controllers\Enrollements;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Http\Requests\CourseAdvanced\getFindByKeyWorkRequest;
use App\Interfaces\Service\Enrollments\ISearchEnrollmentsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class SearchEnrollementsController extends Controller
{
    use ReturnIdInRequestOrAuth;
    public $enrrollementsService;

    public function __construct(ISearchEnrollmentsService $enrrollementsServicel)
    {
        parent::__construct();
        $this->enrrollementsService = $enrrollementsServicel;
    }


    /**
     * Find enrollments by keywords.
     *
     * @param getFindByKeyWorkRequest $request The request object containing the keyword, perPage, and page.
     * @return \Illuminate\Http\JsonResponse The response containing the found courses.
     * @throws \Throwable If an error occurs while searching enrollments.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to perform this action.
     *
     * @OA\Get(
     *     path="/api/enrollments/searchEncorrollments",
     *     summary="Find enrollments by keywords",
     *     description="This endpoint finds enrollments by keywords. Requires authentication token.",
     *     operationId="findEnrollmentsByKeywords",
     *     tags={"Enrollments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="keyword",
     *         required=true,
     *         @OA\Schema(type="string", example="programming")
     *     ),
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
     *         description="Enrollments found successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="courses", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *                 @OA\Property(property="description", type="string", example="A beginner's course on programming")
     *             )),
     *             @OA\Property(property="total", type="integer", example=5),
     *             @OA\Property(property="perPage", type="integer", example=10),
     *             @OA\Property(property="page", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while finding enrollments",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function findEnrollmentsByKeywords(getFindByKeyWorkRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('findEnrollmentsByKeywords', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->enrrollementsService->findEnrollmentsByKeywords($id, $request->keyword, $request->perPage, $request->page);
            return $courses;
        });
    }

    /**
     * Get public and active enrollments for a user.
     *
     * @param getCourseAdvancedRequest $request The request object containing the perPage and page.
     * @return \Illuminate\Http\JsonResponse The response containing the public and active enrollments.
     * @throws \Throwable If an error occurs while retrieving enrollments.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to perform this action.
     * @OA\Get(
     *     path="/api/enrollments/getPublicAndActiveEncorrollments",
     *     summary="Get public and active enrollments",
     *     description="This endpoint retrieves public and active enrollments for a user. Requires authentication token.",
     *     operationId="getPublicAndActiveEnrollments",
     *     tags={"Enrollments"},
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
     *         description="Public and active enrollments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="courses", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *                 @OA\Property(property="description", type="string", example="A beginner's course on programming")
     *             )),
     *             @OA\Property(property="total", type="integer", example=5),
     *             @OA\Property(property="perPage", type="integer", example=10),
     *             @OA\Property(property="page", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving public and active enrollments",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getPublicAndActiveEnrollments(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPublicAndActiveEnrollments', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'PublicAndActiveEnrollmentsUser-' . $id, $request->perPage, $request->page, function () use ($request, $id) {
                return $this->enrrollementsService->getPublicAndActiveEnrollments($id, $request->perPage, $request->page);
            }, 10);
            return $courses;
        });
    }
}
