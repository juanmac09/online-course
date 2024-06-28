<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\CourseAdvanced\getFindByKeyWorkRequest;
use App\Interfaces\Service\CourseAdvanced\ISearchPublicCourseService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

class SearchCoursesController extends Controller
{
    use AttachQualificationsTrait;
    public $courseService;
    public $qualificationService;

    public function __construct(ISearchPublicCourseService $courseService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseService = $courseService;
        $this->qualificationService = $qualificationService;
    }

    /**
     * Find courses by keyword.
     *
     * @param getFindByKeyWorkRequest $request The request object containing the keyword.
     * @return \Illuminate\Http\JsonResponse The response containing the list of courses.
     * @OA\Get(
     *     path="/api/course/search",
     *     summary="Find courses by keyword",
     *     description="Endpoint to find courses by keyword.",
     *     operationId="findByKeyword",
     *     tags={"Course Search"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=true,
     *         description="The keyword to search for courses",
     *         @OA\Schema(type="string", example="Math")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=true,
     *         description="Number of results per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=true,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of courses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Course Title"),
     *                 @OA\Property(property="description", type="string", example="Course Description"),
     *                 @OA\Property(property="status", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving courses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function findByKeyword(getFindByKeyWorkRequest $request)
    {
        Gate::authorize('findByKeyword', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $courses = $this->courseService->findByKeyword($request->keyword, $request->perPage, $request->page);
            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return $courses;
        });
    }


    /**
     * Get the list of public and active courses.
     *
     * @param CourseReadRequest $request The request object containing pagination information.
     * @return \Illuminate\Http\JsonResponse The response containing the list of public and active courses.
     * @OA\Get(
     *     path="/api/course/getPublicAndActiveCourses",
     *     summary="Get public and active courses",
     *     description="Endpoint to get the list of public and active courses.",
     *     operationId="getPublicAndActiveCourses",
     *     tags={"Course Search"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=true,
     *         description="Number of results per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=true,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of public and active courses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Course Title"),
     *                 @OA\Property(property="description", type="string", example="Course Description"),
     *                 @OA\Property(property="status", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving courses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getPublicAndActiveCourses(CourseReadRequest $request)
    {
        Gate::authorize('getPublicAndActiveCourses', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $courses = $this->cacheService->storeInCache('Course', 'PublicAndActiveCourse', $request->perPage, $request->page, function () use ($request) {
                $courses = $this->courseService->getPublicAndActiveCourses(Auth::user()->id, $request->perPage, $request->page);
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);

            return $courses;
        });
    }
}
