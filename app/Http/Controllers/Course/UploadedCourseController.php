<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicReadService;
use App\Interfaces\Service\CourseAdvanced\ICoursePublicWriteService;
use App\Interfaces\Service\CourseAdvanced\IUploadedCourseService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class UploadedCourseController extends Controller
{
    use AttachQualificationsTrait, ReturnIdInRequestOrAuth;
    public $courseService;
    public $coursePublicWriteService;
    public $coursePublicReadService;
    public $qualificationService;
    public function __construct(IUploadedCourseService $courseService, ICoursePublicWriteService $coursePublicWriteService, ICoursePublicReadService $coursePublicReadService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseService = $courseService;
        $this->coursePublicWriteService = $coursePublicWriteService;
        $this->coursePublicReadService = $coursePublicReadService;
        $this->qualificationService = $qualificationService;
    }

    /**
     * Get the list of courses uploaded by the user.
     *
     * @param getCourseAdvancedRequest $request The request object containing pagination information.
     * @return \Illuminate\Http\JsonResponse The response containing the list of uploaded courses.
     * @OA\Get(
     *     path="/api/course/getCourseUploaded",
     *     summary="Get uploaded courses",
     *     description="Endpoint to get the list of courses uploaded by the user.",
     *     operationId="getCoursesUploaded",
     *     tags={"Uploaded Courses"},
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
     *         description="List of uploaded courses",
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
    public function getCoursesUploaded(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getCoursesUploaded', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'CoursesUploadedUser-' . $id, $request->perPage, $request->page, function () use ($request) {
                if ($request->has('id')) {
                    $courses = $this->courseService->getUploadedCourses(null, $request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseService->getUploadedCourses(Auth::user(), null, $request->perPage, $request->page);
                }

                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);
            return $courses;
        });
    }

    /**
     * Make a course public.
     *
     * @param CourseWriteRequest $request The request object containing course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the updated course.
     * @OA\Post(
     *     path="/api/course/makePublicACourse",
     *     summary="Make a course public",
     *     description="Endpoint to make a course public.",
     *     operationId="makePublicACourse",
     *     tags={"Uploaded Courses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the course to be made public",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course made public",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Course Title"),
     *             @OA\Property(property="description", type="string", example="Course Description"),
     *             @OA\Property(property="status", type="integer", example=1)
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
     *         description="An error occurred while updating the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function makePublicACourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePublicACourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->coursePublicWriteService->makePublicACourse($request->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }

    /**
     * Get the list of public courses.
     *
     * @param getCourseAdvancedRequest $request The request object containing pagination information.
     * @return \Illuminate\Http\JsonResponse The response containing the list of public courses.
     * @OA\Get(
     *     path="/api/course/getPublicCourses",
     *     summary="Get public courses",
     *     description="Endpoint to get the list of public courses.",
     *     operationId="getPublicCourses",
     *     tags={"Uploaded Courses"},
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
     *         description="List of public courses",
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
    public function getPublicCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPublicCourses', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'PublicCourseUser-' . $id, $request->perPage, $request->page, function () use ($request) {
                if ($request->has('id')) {
                    $courses = $this->coursePublicReadService->getPublicCourses($request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->coursePublicReadService->getPublicCourses(Auth::user()->id, $request->perPage, $request->page);
                }
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);

            return $courses;
        });
    }
}
