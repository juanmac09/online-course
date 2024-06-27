<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\CommentsWriteRequest;
use App\Interfaces\Service\Comments\ICommentsWriteService;
use Illuminate\Support\Facades\Auth;
use App\Models\Comments;
use Illuminate\Support\Facades\Gate;


class CommentsWriteController extends Controller
{
    public $commentWriteService;

    public function __construct(ICommentsWriteService $commentWriteService)
    {
        parent::__construct();
        $this->commentWriteService = $commentWriteService;
    }


    public function createComments(CommentsWriteRequest $request)
    {
        Gate::authorize('create', [Comments::class, $request->content_id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->createComments(Auth::user()->id, $request->content_id, $request->comment);
            $this -> cacheService -> invalidateGroupCache('Comments');
            return $comment;
        });
    }

    public function updateComments(CommentsWriteRequest $request)
    {
        Gate::authorize('update', [Comments::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->updateComments($request->id, $request->comment);
            $this -> cacheService -> invalidateGroupCache('Comments');
            return $comment;
        });
    }


    public function disableComments(CommentsWriteRequest $request)
    {
        Gate::authorize('disable', [Comments::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->disableComments($request->id);
            $this -> cacheService -> invalidateGroupCache('Comments');
            return $comment;
        });
    }
}
