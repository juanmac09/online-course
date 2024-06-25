<?php

namespace App\Http\Controllers\Enrollements;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollments\EnrollmentsWriteRequest;
use App\Interfaces\Service\Enrollments\IEnrollmentsWriteService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;

class EnrollementsController extends Controller
{
    public $enrrollmentsService;

    public function __construct(IEnrollmentsWriteService $enrrollmentsService) {
        $this->enrrollmentsService = $enrrollmentsService;
    }


    public function enrollInCourse(EnrollmentsWriteRequest $request){
        Gate::authorize('enrollInCourse', Course::class);
        return $this -> handleServiceCall(function () use ($request){
            $enrrollments = $this->enrrollmentsService->enrollInCourse(Auth::user()->id, $request->id);
            return $enrrollments; 
        });
    }


    public function unEnrollInCourse(EnrollmentsWriteRequest $request){
        Gate::authorize('unEnrollInCourse', Course::class);
        return $this -> handleServiceCall(function () use ($request){
            $unEnrrollments = $this->enrrollmentsService->unEnrollInCourse(Auth::user()->id, $request->id);
            return $unEnrrollments;
        });
    }
}
