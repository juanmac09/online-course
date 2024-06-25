<?php

namespace App\Http\Controllers\Qualification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Qualification\QualificationCreateRequest;
use App\Interfaces\Service\Qualification\IQualificationWriteService;
use Illuminate\Support\Facades\Auth;
use App\Models\Qualification;
use Illuminate\Support\Facades\Gate;
class QualificationWriteController extends Controller
{
    public $qualificationService;

    public function __construct(IQualificationWriteService  $qualificationService)
    {
        $this->qualificationService = $qualificationService;
    }


    public function createQualification(QualificationCreateRequest $request)
    {
        Gate::authorize('create', [Qualification::class, $request ->course_id]);
        return $this -> handleServiceCall(function () use ($request){
            $qualification =  $this->qualificationService->createQualification($request->qualification, Auth::user()->id, $request->course_id);
            return $qualification;
        });
            
    }



    public function updateQualification(QualificationCreateRequest $request)
    {   
        Gate::authorize('update', [Qualification::class, $request ->course_id]);
        return $this -> handleServiceCall(function () use ($request){
            $qualification =  $this->qualificationService->updateQualification($request->qualification, Auth::user()->id, $request->course_id);
            return $qualification;
        });
            
    }
}
