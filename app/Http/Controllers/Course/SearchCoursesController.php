<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\CourseAdvanced\getFindByKeyWorkRequest;
use App\Interfaces\Service\CourseAdvanced\ISearchPublicCourseService;
use App\Interfaces\Service\Qualification\IQualificationReadService;
use App\Traits\Course\AttachQualificationsTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

class SearchCoursesController extends Controller
{
    use AttachQualificationsTrait;
    public $courseService;
    public $qualificationService;

    public function __construct(ISearchPublicCourseService $courseService, IQualificationReadService $qualificationService)
    {
        $this->courseService = $courseService;
        $this->qualificationService = $qualificationService;
    }


    public function findByKeyword(getFindByKeyWorkRequest $request)
    {
        Gate::authorize('findByKeyword', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $courses = $this->courseService->findByKeyword($request->keyword, $request->perPage, $request->page);
            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return $courses;
        });
    }



    public function getPublicAndActiveCourses(CourseReadRequest $request)
    {
        Gate::authorize('getPublicAndActiveCourses', Course::class);
        return $this->handleServiceCall(function () use ($request) {
            $courses = $this->courseService->getPublicAndActiveCourses(Auth::user()->id, $request->perPage, $request->page);
            $courses = $this->attachQualificationsToCourses($courses, $this->qualificationService);
            return $courses;
        });
    }
}
