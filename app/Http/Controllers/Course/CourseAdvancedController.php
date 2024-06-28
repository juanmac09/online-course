<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\CourseAdvanced\IAdvancedCourseService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use App\Traits\Course\AttachQualificationsTrait;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Auth;

class CourseAdvancedController extends Controller
{
    use AttachQualificationsTrait, ReturnIdInRequestOrAuth;
    public $courseService;
    public $qualificationService;

    public function __construct(IAdvancedCourseService $courseService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseService = $courseService;
        $this->qualificationService = $qualificationService;
    }

    /**
     * Get enrolled courses.
     *
     * @param getCourseAdvancedRequest $request The request object containing the perPage, page, and optionally id.
     * @return \Illuminate\Http\JsonResponse The response containing the enrolled courses.
     * @throws \Throwable If an error occurs while retrieving enrolled courses.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to perform this action.
     * @OA\Post(
     *     path="/api/course/getEnrolledCourses",
     *     summary="Get enrolled courses",
     *     description="This endpoint retrieves enrolled courses. Requires authentication token.",
     *     operationId="getEnrolledCourses",
     *     security={{"bearerAuth":{}}},
     *     tags={"Courses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"perPage"},
     *             @OA\Property(property="perPage", type="integer", example=10),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="id", type="integer", example=1, description="Optional user ID to retrieve another user's enrolled courses")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enrolled courses retrieved successfully",
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
     *         description="An error occurred while retrieving enrolled courses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getEnrolledCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getEnrolledCourses', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'EnrolledCoursesUser-' . $id, $request->perPage, $request->page, function () use ($request) {
                if ($request->has('id')) {
                    $courses = $this->courseService->getEnrolledCourses(null, $request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseService->getEnrolledCourses(Auth::user(), null, $request->perPage, $request->page);
                }
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);
            return $courses;
        });
    }
}
