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
    use AttachQualificationsTrait,ReturnIdInRequestOrAuth;
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

    public function getCoursesUploaded(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getCoursesUploaded', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this -> getUserIdFromRequestOrAuth($request); 
            $courses = $this ->cacheService -> storeInCache('Course','CoursesUploadedUser-'.$id,$request->perPage,$request -> page,function () use ($request){
                if ($request->has('id')) {
                    $courses = $this->courseService->getUploadedCourses(null, $request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseService->getUploadedCourses(Auth::user(), null, $request->perPage, $request->page);
                }
    
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            },10);
            return $courses;
        });
           
    }


    public function makePublicACourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePublicACourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->coursePublicWriteService->makePublicACourse($request->id);
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
    }


    public function getPublicCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPublicCourses', Course::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            $id = $this -> getUserIdFromRequestOrAuth($request); 
            $courses = $this ->cacheService -> storeInCache('Course','PublicCourseUser-'.$id,$request->perPage,$request -> page,function () use ($request){
                if ($request->has('id')) {
                    $courses = $this->coursePublicReadService->getPublicCourses($request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->coursePublicReadService->getPublicCourses(Auth::user()->id, $request->perPage, $request->page);
                }
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            },10);
            
            return $courses;
        });
       
    }
}
