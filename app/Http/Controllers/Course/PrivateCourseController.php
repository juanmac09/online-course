<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateReadService;
use App\Interfaces\Service\CourseAdvanced\ICoursePrivateWriteService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use App\Traits\Course\AttachQualificationsTrait;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Auth;

class PrivateCourseController extends Controller
{
    use AttachQualificationsTrait, ReturnIdInRequestOrAuth;
    public $courseWriteService;
    public $courseReadService;
    public $qualificationService;
    public function __construct(ICoursePrivateWriteService $courseWriteService, ICoursePrivateReadService $courseReadService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseWriteService = $courseWriteService;
        $this->courseReadService = $courseReadService;
        $this->qualificationService = $qualificationService;
    }


    /**
     * Make a course private.
     *
     * @param CourseWriteRequest $request The request object containing the course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the updated course.
     * @OA\Post(
     *     path="/api/course/makePrivateACourse",
     *     summary="Make course private",
     *     description="Endpoint to make a course private.",
     *     operationId="makePrivateCourse",
     *     tags={"Private Course"},
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
     *         description="Course status changed to private successfully",
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
    public function makePrivateCourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePrivateCourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->makePrivateACourse($request->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $course;
        });
    }

    /**
     * Get the list of private courses.
     *
     * @param getCourseAdvancedRequest $request The request object containing pagination information.
     * @return \Illuminate\Http\JsonResponse The response containing the list of private courses.
     * @OA\Get(
     *     path="/api/course/getPrivateCourses",
     *     summary="Get private courses",
     *     description="Endpoint to get the list of private courses.",
     *     operationId="getPrivateCourses",
     *     tags={"Private Course"},
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
     *         description="List of private courses",
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
    public function getPrivateCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPrivateCourses', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this->getUserIdFromRequestOrAuth($request);
            $courses = $this->cacheService->storeInCache('Course', 'PrivateCourseUser-' . $id, $request->perPage, $request->page, function () use ($request) {
                if ($request->has('id')) {
                    $courses = $this->courseReadService->getPrivateCourses($request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseReadService->getPrivateCourses(Auth::user()->id, $request->perPage, $request->page);
                }
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            }, 10);
            return $courses;
        });
    }
}
