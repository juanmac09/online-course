<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAdvanced\getCourseAdvancedRequest;
use App\Interfaces\Service\Comments\IReportCommentsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Comments;
use App\Traits\Helper\ReturnIdInRequestOrAuth;
use Illuminate\Support\Facades\Gate;
class ReportCommentsController extends Controller
{
    use ReturnIdInRequestOrAuth;
    public $commentsService;

    public function __construct(IReportCommentsService $commentsService) {
        parent::__construct();
        $this->commentsService = $commentsService;
    }

    public function getCommentByUser(getCourseAdvancedRequest $request){

        if ($request->has('id')) {
            Gate::authorize('getCommentByUser', Comments::class);
        }
        return $this -> handleServiceCall(function () use ($request){
            $id = $this -> getUserIdFromRequestOrAuth($request);
            $comments = $this ->cacheService -> storeInCache('Comments','CommentsByUser-'.$id,$request->perPage,$request -> page,function () use ($request,$id){
                return $this->commentsService->getCommentsByUser($id,$request->perPage,$request -> page);
            },10);
            return $comments;
        });
        
    }
}
