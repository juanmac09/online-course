<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\getIdContentRequest;
use App\Http\Requests\Content\getPublicContentRequest;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyReadService;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyWriteService;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Gate;

class UploadedContentController extends Controller
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
     * Get public content.
     *
     * @param getPublicContentRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing public content.
     * @OA\Get(
     *     path="/api/content/getPublicContent",
     *     summary="Get public content",
     *     description="Endpoint to get public content.",
     *     operationId="getPublicContent",
     *     tags={"Uploaded Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the course",
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
     *         description="List of public content",
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
     *         description="An error occurred while retrieving public content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getPublicContent(getPublicContentRequest $request)
    {
        Gate::authorize('getPublicContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {

            $contents = $this->cacheService->storeInCache('Content', 'PublicContentCourse-'.$request ->id, $request->perPage, $request->page, function () use ($request) {
                return $this->contentReadService->getContentPrivacy($request->id, 1, $request->perPage, $request->page);
            }, 10);
            return $contents;
        });
    }


    /**
     * Make a content public.
     *
     * @param getIdContentRequest $request The request object containing the content ID.
     * @return \Illuminate\Http\JsonResponse The response indicating the success of making content public.
     * @OA\Put(
     *     path="/api/content/makePublicAContent",
     *     summary="Make a content public",
     *     description="Endpoint to make a content public.",
     *     operationId="makePublicAContent",
     *     tags={"Uploaded Content Management"},
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
     *         description="Content made public successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content privacy changed to public.")
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
     *         description="An error occurred while making content public",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function makePublicAContent(getIdContentRequest $request)
    {
        Gate::authorize('makePublicAContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->changeContentPrivacy($request->id, 1);
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }
}
