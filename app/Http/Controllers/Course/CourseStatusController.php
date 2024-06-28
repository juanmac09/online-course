<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusReadService;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusWriteService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class CourseStatusController extends Controller
{
    use AttachQualificationsTrait, ReturnIdInRequestOrAuth;
    public $courseStatusReadService;
    public $courseStatusWriteService;
    public $qualificationService;

    public function __construct(ICourseStatusReadService $courseStatusReadService, ICourseStatusWriteService $courseStatusWriteService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseStatusReadService = $courseStatusReadService;
        $this->courseStatusWriteService = $courseStatusWriteService;
        $this->qualificationService = $qualificationService;
    }

    /**
     * Change the active status of a course.
     *
     * @param CourseWriteRequest $request The request object containing the course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the updated course.
     * @OA\Post(
     *     path="/api/course/changeActiveStatusToCourse",
     *     summary="Change course active status",
     *     description="Endpoint to change the active status of a course.",
     *     operationId="changeActiveStatusToCourse",
     *     tags={"Course Status"},
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
     *         description="Course status changed successfully",
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
     *         description="An error occurred while changing course status",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function changeActiveStatusToCourse(CourseWriteRequest $request)
    {
        Gate::authorize('changeActiveStatusToCourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseStatusWriteService->changeCourseStatus($request->id, 1);
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }

    /**
     * Get the list of inactive courses.
     *
     * @param getCourseAdvancedRequest $request The request object containing pagination information.
     * @return \Illuminate\Http\JsonResponse The response containing the list of inactive courses.
     * @OA\Get(
     *     path="/api/course/getDesactiveCourses",
     *     summary="Get inactive courses",
     *     description="Endpoint to get the list of inactive courses.",
     *     operationId="getDesactiveCourses",
     *     tags={"Course Status"},
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
     *         description="List of inactive courses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Course Title"),
     *                 @OA\Property(property="description", type="string", example="Course Description"),
     *                 @OA\Property(property="status", type="integer", example=0)
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
    public function getDesactiveCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getDesactiveCourses', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'DesactiveCourseUser-' . $id, $request->perPage, $request->page, function () use ($request) {
                if ($request->has('id')) {
                    $courses = $this->courseStatusReadService->getCourseStatus($request->id, 0, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseStatusReadService->getCourseStatus(Auth::user()->id, 0, $request->perPage, $request->page);
                }
                return  $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);

            return $courses;
        });
    }
}
