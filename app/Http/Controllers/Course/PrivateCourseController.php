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
use Illuminate\Support\Facades\Auth;

class PrivateCourseController extends Controller
{
    use AttachQualificationsTrait;
    public $courseWriteService;
    public $courseReadService;
    public $qualificationService;
    public function __construct(ICoursePrivateWriteService $courseWriteService, ICoursePrivateReadService $courseReadService, IQualificationReadService $qualificationService)
    {
        $this->courseWriteService = $courseWriteService;
        $this->courseReadService = $courseReadService;
        $this->qualificationService = $qualificationService;
    }



    public function makePrivateCourse(CourseWriteRequest $request)
    {
        Gate::authorize('makePrivateCourse', [Course::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $course = $this->courseWriteService->makePrivateACourse($request->id);
            return $course;
        });
    }


    public function getPrivateCourses(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPrivateCourses', Course::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            if ($request->has('id')) {
                $courses = $this->courseReadService->getPrivateCourses($request->id, $request->perPage, $request->page);
            } else {
                $courses = $this->courseReadService->getPrivateCourses(Auth::user()->id, $request->perPage, $request->page);
            }
            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return $courses;
        });
    }
}
