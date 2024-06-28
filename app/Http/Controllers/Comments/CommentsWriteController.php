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

    /**
     * Create a new comment.
     *
     * @param CommentsWriteRequest $request The request object containing comment details.
     * @return \Illuminate\Http\JsonResponse The response containing the created comment.
     * @OA\Post(
     *     path="/api/comments/create",
     *     summary="Create a new comment",
     *     description="Endpoint to create a new comment.",
     *     operationId="createComments",
     *     tags={"Comments Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content_id", "comment"},
     *             @OA\Property(property="content_id", type="integer", example=1),
     *             @OA\Property(property="comment", type="string", example="This is a new comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Created comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="content_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="comment", type="string", example="This is a new comment."),
     *             @OA\Property(property="created_at", type="string", example="2024-06-28 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-28 12:00:00")
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
     *         description="An error occurred while creating comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function createComments(CommentsWriteRequest $request)
    {
        Gate::authorize('create', [Comments::class, $request->content_id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->createComments(Auth::user()->id, $request->content_id, $request->comment);
            $this->cacheService->invalidateGroupCache('Comments');
            return $comment;
        });
    }
    /**
     * Update an existing comment.
     *
     * @param CommentsWriteRequest $request The request object containing comment details.
     * @return \Illuminate\Http\JsonResponse The response containing the updated comment.
     * @OA\Post(
     *     path="/api/comments/update",
     *     summary="Update an existing comment",
     *     description="Endpoint to update an existing comment.",
     *     operationId="updateComments",
     *     tags={"Comments Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "comment"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="comment", type="string", example="This is an updated comment.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="content_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="comment", type="string", example="This is an updated comment."),
     *             @OA\Property(property="created_at", type="string", example="2024-06-28 12:00:00"),
     *             @OA\Property(property="updated_at", type="string", example="2024-06-28 12:00:00")
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
     *         response=404,
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while updating comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateComments(CommentsWriteRequest $request)
    {
        Gate::authorize('update', [Comments::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->updateComments($request->id, $request->comment);
            $this->cacheService->invalidateGroupCache('Comments');
            return $comment;
        });
    }

    /**
     * Disable a comment.
     *
     * @param CommentsWriteRequest $request The request object containing comment ID.
     * @return \Illuminate\Http\JsonResponse The response indicating the disabled comment.
     * @OA\Post(
     *     path="/api/comments/disable",
     *     summary="Disable a comment",
     *     description="Endpoint to disable a comment.",
     *     operationId="disableComments",
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
     *         description="Comment disabled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment disabled successfully.")
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
     *         response=404,
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Comment not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while disabling comment",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function disableComments(CommentsWriteRequest $request)
    {
        Gate::authorize('disable', [Comments::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $comment = $this->commentWriteService->disableComments($request->id);
            $this->cacheService->invalidateGroupCache('Comments');
            return $comment;
        });
    }
}
