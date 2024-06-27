<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\CommentsReadRequest;
use App\Interfaces\Service\Comments\ICommentsReadService;
use App\Models\Comments;
use Illuminate\Support\Facades\Gate;
class CommentsReadController extends Controller
{
    public $commentReadService;

    public function __construct(ICommentsReadService $commentReadService) {
        parent::__construct();
        $this->commentReadService = $commentReadService;
    }

    public function getCommentsbyContent(CommentsReadRequest $request){
        Gate::authorize('getCommentsbyContent', [Comments::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $comment = $this ->cacheService -> storeInCache('Comments','CommentsByContentId-'.$request->id,$request->perPage,$request -> page,function () use ($request){
                return  $this -> commentReadService -> getCommentsByContent($request -> id,$request ->perPage, $request ->page);
            },10);
            return $comment;
        }); 
    }
}
