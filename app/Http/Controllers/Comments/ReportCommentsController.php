<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\Comments\IReportCommentsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Comments;
use Illuminate\Support\Facades\Gate;
class ReportCommentsController extends Controller
{
    public $commentsService;

    public function __construct(IReportCommentsService $commentsService) {
        $this->commentsService = $commentsService;
    }

    public function getCommentByUser(getCourseAdvancedRequest $request){

        if ($request->has('id')) {
            Gate::authorize('getCommentByUser', Comments::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            $id = $request -> has('id') ? $request -> id : Auth::user() -> id;
            $comments = $this->commentsService->getCommentsByUser($id,$request->perPage,$request -> page);
            return $comments;
        });
        
    }
}
