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
    use AttachQualificationsTrait,ReturnIdInRequestOrAuth;
    public $courseService;
    public $qualificationService;

    public function __construct(IAdvancedCourseService $courseService, IQualificationReadService $qualificationService)
    {
        parent::__construct();
        $this->courseService = $courseService;
        $this->qualificationService = $qualificationService;
    }


    public function getEnrolledCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getEnrolledCourses', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this -> getUserIdFromRequestOrAuth($request); 
            $courses = $this->cacheService->storeInCache('Course', 'EnrolledCoursesUser-'.$id, $request->perPage, $request->page, function () use ($request) {
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
