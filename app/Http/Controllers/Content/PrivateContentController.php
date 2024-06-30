<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\getIdContentRequest;
use App\Http\Requests\Content\getPublicContentRequest;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyReadService;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyWriteService;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Gate;

class PrivateContentController extends Controller
{
    public $contentReadService;
    public $contentWriteService;

    public function __construct(IContentPrivacyReadService $contentReadService, IContentPrivacyWriteService $contentWriteService)
    {
        parent::__construct();
        $this->contentReadService = $contentReadService;
        $this->contentWriteService = $contentWriteService;
    }

    /**
     * Get private content.
     *
     * @param getPublicContentRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing private content.
     * @OA\Get(
     *     path="/api/content/getPrivateContent",
     *     summary="Get private content",
     *     description="Endpoint to get private content.",
     *     operationId="getPrivateContent",
     *     tags={"Private Content Management"},
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
     *         description="List of private content",
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
     *         description="An error occurred while retrieving private content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getPrivateContent(getPublicContentRequest $request)
    {
        Gate::authorize('getPrivateContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->cacheService->storeInCache('Content', 'PrivateContentCourse-'.$request -> id, $request->perPage, $request->page, function () use ($request) {
                return $this->contentReadService->getContentPrivacy($request->id, 0, $request->perPage, $request->page);
            }, 10);
            return $content;
        });
    }

    /**
     * Make content private.
     *
     * @param getIdContentRequest $request The request object containing content ID.
     * @return \Illuminate\Http\JsonResponse The response containing the private content.
     * @OA\Put(
     *     path="/api/content/makePrivateAContent",
     *     summary="Make content private",
     *     description="Endpoint to change the privacy of content to private.",
     *     operationId="makePrivateAContent",
     *     tags={"Private Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content made private",
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
     *         description="An error occurred while changing content privacy",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function makePrivateAContent(getIdContentRequest $request)
    {
        Gate::authorize('makePrivateAContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->changeContentPrivacy($request->id, 0);
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }
}
