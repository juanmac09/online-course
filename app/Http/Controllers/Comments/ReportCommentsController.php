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
    /**
     * Get comments made by a user.
     *
     * @param getCourseAdvancedRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing the comments made by the user.
     * @OA\Post(
     *     path="/api/comments/getCommentsByUser",
     *     summary="Get comments made by a user",
     *     description="Endpoint to retrieve comments made by a specific user.",
     *     operationId="getCommentByUser",
     *     tags={"Comments Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comments made by the user",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="content_id", type="integer", example=1),
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
