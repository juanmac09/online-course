<?php

namespace App\Http\Controllers\Enrollements;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Http\Requests\CourseAdvanced\getFindByKeyWorkRequest;
use App\Interfaces\Service\Enrollments\ISearchEnrollmentsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;

class SearchEnrollementsController extends Controller
{
    use ReturnIdInRequestOrAuth;
    public $enrrollementsService;

    public function __construct(ISearchEnrollmentsService $enrrollementsServicel)
    {
        $this->enrrollementsService = $enrrollementsServicel;
    }



    public function findEnrollmentsByKeywords(getFindByKeyWorkRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('findEnrollmentsByKeywords', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this -> getUserIdFromRequestOrAuth($request);
            $courses = $this->enrrollementsService->findEnrollmentsByKeywords($id, $request->keyword, $request->perPage, $request->page);
            return $courses;
        });
    }


    public function getPublicAndActiveEnrollments(getCourseAdvancedRequest $request)
    {
        if ($request->has('id')) {
            Gate::authorize('getPublicAndActiveEnrollments', Course::class);
        }
        return $this->handleServiceCall(function () use ($request) {
            $id = $this -> getUserIdFromRequestOrAuth($request);
            $courses = $this ->cacheService -> storeInCache('Course','PublicAndActiveEnrollmentsUser-'.$id,$request->perPage,$request -> page,function () use ($request,$id){
                return $this->enrrollementsService->getPublicAndActiveEnrollments($id, $request->perPage, $request->page);
            },10);
            return $courses;
        });
    }
}
