<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Interfaces\Service\Course\ICourseReadService;
use App\Interfaces\Service\Course\ICourseWriteService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class CourseManagementController extends Controller
{
    use AttachQualificationsTrait;
    public $courseWriteService;
    public $courseReadService;
    public $qualificationService;
    public function __construct(ICourseWriteService $courseWriteService, ICourseReadService $courseReadService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseWriteService = $courseWriteService;
        $this->courseReadService = $courseReadService;
        $this->qualificationService = $qualificationService;
    }
    /**
     * Create a new course.
     *
     * @param CourseWriteRequest $request The request object containing the title and description.
     * @return \Illuminate\Http\JsonResponse The response containing the created course data.
     * @throws \Throwable If an error occurs while creating the course.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to create a course.
     * @OA\Post(
     *     path="/api/course/create",
     *     summary="Create a new course",
     *     description="This endpoint is used to create a new course.",
     *     operationId="createCourse",
     *     tags={"Courses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *             @OA\Property(property="description", type="string", example="A beginner's course on programming")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="course", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *                 @OA\Property(property="description", type="string", example="A beginner's course on programming"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-12T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-12T15:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while creating the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function createCourse(CourseWriteRequest $request)
    {
        Gate::authorize('create', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->createCourse($request->only('title', 'description'), Auth::user()->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }
    /**
     * Get all courses.
     *
     * @param CourseReadRequest $request The request object containing the perPage and page parameters.
     * @return \Illuminate\Http\JsonResponse The response containing the list of courses.
     * @throws \Throwable If an error occurs while retrieving courses.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to retrieve courses.
     * @OA\Get(
     *     path="/api/course/all",
     *     summary="Get all courses",
     *     description="This endpoint retrieves all courses.",
     *     operationId="getAllCourse",
     *     tags={"Courses"},
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
     *         description="Courses retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="courses", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *                 @OA\Property(property="description", type="string", example="A beginner's course on programming"),
     *                 @OA\Property(property="qualifications", type="array", @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Programming Fundamentals"),
     *                     @OA\Property(property="score", type="integer", example=85)
     *                 ))
     *             )),
     *             @OA\Property(property="total", type="integer", example=5),
     *             @OA\Property(property="perPage", type="integer", example=10),
     *             @OA\Property(property="page", type="integer", example=1)
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
    public function getAllCourse(CourseReadRequest $request)
    {
        Gate::authorize('index', Course::class);
        return $this->handleServiceCall(function () use ($request) {

            $courses = $this->cacheService->storeInCache('Course', 'AllCourse', $request->perPage, $request->page, function () use ($request) {
                $courses = $this->courseReadService->getAllCourse($request->perPage, $request->page);
                return  $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);

            return $courses;
        });
    }
    /**
     * Update a course.
     *
     * @param CourseWriteRequest $request The request object containing the course ID, title, and description.
     * @return \Illuminate\Http\JsonResponse The response containing the updated course data.
     * @throws \Throwable If an error occurs while updating the course.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to update the course.
     * @OA\Put(
     *     path="/api/course/update",
     *     summary="Update a course",
     *     description="This endpoint updates a course.",
     *     operationId="updateCourse",
     *     tags={"Courses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *             @OA\Property(property="description", type="string", example="An updated course description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Introduction to Programming"),
     *             @OA\Property(property="description", type="string", example="An updated course description"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-27T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateCourse(CourseWriteRequest $request)
    {
        Gate::authorize('update', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->updateCourse($request->id, $request->only('title', 'description'));
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }


    /**
     * Disable a course.
     *
     * @param CourseWriteRequest $request The request object containing the course ID.
     * @return \Illuminate\Http\JsonResponse The response indicating success or failure of disabling the course.
     * @throws \Throwable If an error occurs while disabling the course.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to disable the course.
     * @OA\Put(
     *     path="/api/course/disable",
     *     summary="Disable a course",
     *     description="This endpoint disables a course.",
     *     operationId="disableCourse",
     *     tags={"Courses"},
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
     *         description="Course disabled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Course disabled successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while disabling the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function disableCourse(CourseWriteRequest $request)
    {
        Gate::authorize('disable', [Course::class, $request->id]);

        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->disableCourse($request->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }
}
