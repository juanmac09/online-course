<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\Course\CourseWriteRequest;
use App\Interfaces\Service\Course\ICourseReadService;
use App\Interfaces\Service\Course\ICourseWriteService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class CourseManagementController extends Controller
{
    use AttachQualificationsTrait;
    public $courseWriteService;
    public $courseReadService;
    public $qualificationService;
    public function __construct(ICourseWriteService $courseWriteService,ICourseReadService $courseReadService,IQualificationReadService $qualificationService )
    {
        parent::__construct();
        $this->courseWriteService = $courseWriteService;
        $this->courseReadService = $courseReadService;
        $this->qualificationService = $qualificationService;
    }

    public function createCourse(CourseWriteRequest $request)
    {
        Gate::authorize('create', Course::class);
        return $this -> handleServiceCall(function () use($request){
            $course = $this->courseWriteService->createCourse($request->only('title', 'description'), Auth::user()->id);
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
    }

    public function getAllCourse(CourseReadRequest $request){
        Gate::authorize('index', Course::class);
        return $this -> handleServiceCall(function () use ($request){

            $courses = $this ->cacheService -> storeInCache('Course','AllCourse',$request->perPage,$request -> page,function () use ($request){
                $courses = $this-> courseReadService -> getAllCourse($request -> perPage, $request -> page);
                return  $this -> attachQualificationsToCourses($courses,$this->qualificationService);
            },10);
            
            return $courses;
        });
    }

    public function updateCourse(CourseWriteRequest $request)
    {
        Gate::authorize('update', [Course::class,$request->id ]);
        return $this -> handleServiceCall(function () use ($request){
            $course = $this->courseWriteService->updateCourse($request->id, $request->only('title', 'description'));
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
    }

    public function disableCourse(CourseWriteRequest $request)
    {
        Gate::authorize('disable', [Course::class,$request->id ]);

        return $this -> handleServiceCall(function () use ($request){
            $course = $this->courseWriteService->disableCourse($request->id);
            $this -> cacheService -> invalidateGroupCache('Course');
            return $course;
        });
        
    }
}
