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
    /**
     * Get comments by content ID.
     *
     * @param CommentsReadRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing comments for the content.
     * @OA\Get(
     *     path="/api/comments/getCommentsbyContent",
     *     summary="Get comments by content ID",
     *     description="Endpoint to get comments based on content ID.",
     *     operationId="getCommentsbyContent",
     *     tags={"Comments Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the content",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=true,
     *         description="Number of results per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=true,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content_id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="comment", type="string", example="This is a comment."),
     *                 @OA\Property(property="created_at", type="string", example="2024-06-28 12:00:00"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-06-28 12:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving comments",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
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
