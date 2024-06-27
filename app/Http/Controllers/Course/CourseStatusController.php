<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusReadService;
use App\Interfaces\Service\CourseAdvanced\ICourseStatusWriteService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class CourseStatusController extends Controller
{
    use AttachQualificationsTrait,ReturnIdInRequestOrAuth;
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


    public function changeActiveStatusToCourse(CourseWriteRequest $request)
    {
        Gate::authorize('changeActiveStatusToCourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseStatusWriteService->changeCourseStatus($request->id, 1);
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
    }


    public function getDesactiveCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getDesactiveCourses', Course::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            $id = $this -> getUserIdFromRequestOrAuth($request);
            $courses = $this ->cacheService -> storeInCache('Course','DesactiveCourseUser-'.$id,$request->perPage,$request -> page,function () use ($request){
                if ($request->has('id')) {
                    $courses = $this->courseStatusReadService->getCourseStatus($request->id, 0, $request->perPage, $request->page);
                } else {
                    $courses = $this->courseStatusReadService->getCourseStatus(Auth::user()->id, 0, $request->perPage, $request->page);
                }
                return  $this->attachQualificationsToCourses($courses, $this->qualificationService);
            },10);
            
            return $courses;
        });
    }
}
