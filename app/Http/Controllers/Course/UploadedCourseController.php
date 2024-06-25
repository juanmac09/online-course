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
use Illuminate\Support\Facades\Gate;

class UploadedCourseController extends Controller
{
    use AttachQualificationsTrait;
    public $courseService;
    public $coursePublicWriteService;
    public $coursePublicReadService;
    public $qualificationService;
    public function __construct(IUploadedCourseService $courseService, ICoursePublicWriteService $coursePublicWriteService, ICoursePublicReadService $coursePublicReadService, IQualificationReadService $qualificationService)
    {
        $this->courseService = $courseService;
        $this->coursePublicWriteService = $coursePublicWriteService;
        $this->coursePublicReadService = $coursePublicReadService;
        $this->qualificationService = $qualificationService;
    }

    public function getCoursesUploaded(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getCoursesUploaded', Course::class);
        }
        try {
            if ($request->has('id')) {
                $courses = $this->courseService->getUploadedCourses(null, $request->id, $request->perPage, $request->page);
            } else {
                $courses = $this->courseService->getUploadedCourses(Auth::user(), null, $request->perPage, $request->page);
            }

            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return response()->json(['success' => true, 'courses' => $courses], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }


    public function makePublicACourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePublicACourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->coursePublicWriteService->makePublicACourse($request->id);
            return $course;
        });
    }


    public function getPublicCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPublicCourses', Course::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            if ($request->has('id')) {
                $courses = $this->coursePublicReadService->getPublicCourses($request->id, $request->perPage, $request->page);
            } else {
                $courses = $this->coursePublicReadService->getPublicCourses(Auth::user()->id, $request->perPage, $request->page);
            }
            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return $courses;
        });
       
    }
}
