<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\getIdContentRequest;
use App\Http\Requests\Content\getPublicContentRequest;
use App\Interfaces\Service\ContentAdvanced\IStatusContentReadService;
use App\Interfaces\Service\ContentAdvanced\IStatusContentWriteService;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Gate;

class ContentStatusController extends Controller
{
    public $contentReadService;
    public $contentWriteService;

    public function __construct(IStatusContentReadService $contentReadService, IStatusContentWriteService $contentWriteService)
    {
        parent::__construct();
        $this->contentReadService = $contentReadService;
        $this->contentWriteService = $contentWriteService;
    }


    /**
     * Get status of deactivated content.
     *
     * @param getPublicContentRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing the status of deactivated content.
     * @OA\Get(
     *     path="/api/content/getStatusContent",
     *     summary="Get status of deactivated content",
     *     description="Endpoint to get the status of deactivated content.",
     *     operationId="getStatusDesactiveContent",
     *     tags={"Content Status Management"},
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
     *         description="List of deactivated content",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Content Title"),
     *                 @OA\Property(property="description", type="string", example="Content Description"),
     *                 @OA\Property(property="content", type="string", example="Content Data")
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
     *         description="An error occurred while retrieving content status",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getStatusDesactiveContent(getPublicContentRequest $request)
    {
        Gate::authorize('getStatusDesactiveContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->cacheService->storeInCache('Content', 'StatusDesactiveContent', $request->perPage, $request->page, function () use ($request) {
                return $this->contentReadService->getContentStatus($request->id, 0, $request->perPage, $request->page);
            }, 10);
            return $content;
        });
    }


    /**
     * Change status of content to active.
     *
     * @param getIdContentRequest $request The request object containing content ID.
     * @return \Illuminate\Http\JsonResponse The response containing the activated content.
     * @OA\Post(
     *     path="/api/content/changeStatusContent",
     *     summary="Activate content",
     *     description="Endpoint to change the status of content to active.",
     *     operationId="changeStatusActiveContent",
     *     tags={"Content Status Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content activated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Content Title"),
     *             @OA\Property(property="description", type="string", example="Content Description"),
     *             @OA\Property(property="content", type="string", example="Content Data")
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
     *         description="An error occurred while changing content status",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function changeStatusActiveContent(getIdContentRequest $request)
    {
        Gate::authorize('changeStatusActiveContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->changeContentStatus($request->id, 1);
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }
}
