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
    use AttachQualificationsTrait,ReturnIdInRequestOrAuth;
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



    public function makePrivateCourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePrivateCourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->makePrivateACourse($request->id);
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
    }


    public function getPrivateCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPrivateCourses', Course::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            $id = $this -> getUserIdFromRequestOrAuth($request); 
            $courses = $this ->cacheService -> storeInCache('Course','PrivateCourseUser-'.$id,$request->perPage,$request -> page,function () use ($request){
                if ($request->has('id')) {
                    $courses = $this->courseReadService->getPrivateCourses($request->id, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseReadService->getPrivateCourses(Auth::user()->id, $request->perPage, $request->page);
                }
                return $this->attachQualificationsToCourses($courses, $this->qualificationService);
            },10);
            return $courses;
        });
    }
}
