<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\getPublicContentRequest;
use App\Interfaces\Service\ContentAdvanced\ISearchContentService;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Gate;

class SearchContentController extends Controller
{
    public $courseService;

    public function __construct(ISearchContentService $courseService)
    {
        parent::__construct();
        $this->courseService = $courseService;
    }

    /**
     * Get public and active content.
     *
     * @param getPublicContentRequest $request The request object containing pagination details.
     * @return \Illuminate\Http\JsonResponse The response containing public and active content.
     * @OA\Get(
     *     path="/api/content/getPublicAndActiveContent",
     *     summary="Get public and active content",
     *     description="Endpoint to get public and active content.",
     *     operationId="getPublicAndActiveContent",
     *     tags={"Search Content Management"},
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
     *         description="List of public and active content",
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
     *         description="An error occurred while retrieving public and active content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getPublicAndActiveContent(getPublicContentRequest $request)
    {
        Gate::authorize('getPublicAndActiveContent', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->cacheService->storeInCache('Content', 'PublicAndActiveContent', $request->perPage, $request->page, function () use ($request) {
                return $this->courseService->getPublicAndActiveContent($request->id, $request->perPage, $request->page);
            }, 10);
            return $content;
        });
    }
}
