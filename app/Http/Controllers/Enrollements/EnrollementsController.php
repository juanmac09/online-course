<?php

namespace App\Http\Controllers\Enrollements;

use App\Events\SuscriptionToCourse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollments\EnrollmentsWriteRequest;
use App\Interfaces\Service\Enrollments\IEnrollmentsWriteService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

class EnrollementsController extends Controller
{
    public $enrrollmentsService;

    public function __construct(IEnrollmentsWriteService $enrrollmentsService)
    {
        parent::__construct();
        $this->enrrollmentsService = $enrrollmentsService;
    }

    /**
     * Enrolls a user in a course.
     *
     * @param EnrollmentsWriteRequest $request The request object containing the course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the success status or error message.
     * @throws \Throwable If an error occurs while enrolling the user in the course.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to enroll in the course.
     * @OA\Post(
     *     path="/api/enrollments/create",
     *     summary="Enroll in a course",
     *     description="This endpoint enrolls a user in a course.",
     *     operationId="enrollInCourse",
     *     tags={"Enrollments"},
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
     *         description="User enrolled in the course successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Enrollment successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while enrolling the user in the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function enrollInCourse(EnrollmentsWriteRequest $request)
    {
        Gate::authorize('enrollInCourse', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $enrrollments = $this->enrrollmentsService->enrollInCourse(Auth::user()->id, $request->id);
            SuscriptionToCourse::dispatch(Auth::user()->id, $request->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $enrrollments;
        });
    }

    /**
     * Unenrolls a user from a course.
     *
     * @param EnrollmentsWriteRequest $request The request object containing the course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the success status or error message.
     * @throws \Throwable If an error occurs while unenrolling the user from the course.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to unenroll from the course.
     * @OA\Post(
     *     path="/api/enrollments/unEnroll",
     *     summary="Unenroll from a course",
     *     description="This endpoint unenrolls a user from a course.",
     *     operationId="unEnrollInCourse",
     *     tags={"Enrollments"},
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
     *         description="User unenrolled from the course successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unenrollment successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while unenrolling the user from the course",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function unEnrollInCourse(EnrollmentsWriteRequest $request)
    {
        Gate::authorize('unEnrollInCourse', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $unEnrrollments = $this->enrrollmentsService->unEnrollInCourse(Auth::user()->id, $request->id);
            $this->cacheService->invalidateGroupCache('Course');
            return $unEnrrollments;
        });
    }
}
