<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ContentReadRequest;
use App\Http\Requests\Content\UpdateContentOrderRequest;
use App\Http\Requests\Content\UploadContentRequest;
use App\Interfaces\Service\Content\IContentReadService;
use App\Interfaces\Service\Content\IContentWriteService;
use App\Models\CourseContent;
use Illuminate\Support\Facades\Gate;

class ContentManagementController extends Controller
{

    public $contentWriteService;
    public $contentReadService;

    public function __construct(IContentWriteService $contentWriteService, IContentReadService $contentReadService)
    {
        parent::__construct();
        $this->contentWriteService = $contentWriteService;
        $this->contentReadService = $contentReadService;
    }

    /**
     * Upload new multimedia content.
     *
     * @param UploadContentRequest $request The request object containing multimedia content details.
     * @return \Illuminate\Http\JsonResponse The response containing the uploaded multimedia content.
     *
     * @OA\Post(
     *     path="/api/content/upload",
     *     summary="Upload new multimedia content",
     *     description="Endpoint to upload new multimedia content (JPEG, PNG, JPG, GIF, MP4, AVI, MOV, WMV) to a course.",
     *     operationId="uploadMultimediaContent",
     *     tags={"Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="content", type="string", format="binary"),
     *                 @OA\Property(property="title", type="string", example="Content Title"),
     *                 @OA\Property(property="description", type="string", example="Content Description"),
     *                 @OA\Property(property="course_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Multimedia content uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Content Title"),
     *             @OA\Property(property="description", type="string", example="Content Description"),
     *             @OA\Property(property="file_url", type="string", example="http://example.com/uploads/content/file.jpg"),
     *             @OA\Property(property="course_id", type="integer", example=1)
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
     *         description="An error occurred while uploading multimedia content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function uploadContent(UploadContentRequest $request)
    {

        Gate::authorize('create', [CourseContent::class, $request->course_id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->uploadContent($request->only('title', 'description', 'content', 'course_id'));
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }
    /**
     * Get content for a specific course.
     *
     * @param ContentReadRequest $request The request object containing course ID.
     * @return \Illuminate\Http\JsonResponse The response containing the content for the course.
     * @OA\Get(
     *     path="/api/content/course-content",
     *     summary="Get content for a course",
     *     description="Endpoint to get the content for a specific course.",
     *     operationId="getContentForCourse",
     *     tags={"Content Management"},
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
     *         description="List of content for the course",
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
     *         description="An error occurred while retrieving content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function getContentForCourse(ContentReadRequest $request)
    {
        Gate::authorize('getContentForCourse', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $contents = $this->cacheService->storeInCache('Content', 'contentForCourse', $request->perPage, $request->page, function () use ($request) {
                return $this->contentReadService->getContentForCourse($request->id, $request->perPage, $request->page);
            }, 10);
            return $contents;
        });
    }

    /**
     * Update existing content.
     *
     * @param UploadContentRequest $request The request object containing updated content details.
     * @return \Illuminate\Http\JsonResponse The response containing the updated content.
     *
     * @OA\Post(
     *     path="/api/content/update",
     *     summary="Update existing content",
     *     description="Endpoint to update existing content.",
     *     operationId="updateContent",
     *     tags={"Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", format="binary"),
     *                 @OA\Property(property="title", type="string", example="Updated Content Title"),
     *                 @OA\Property(property="description", type="string", example="Updated Content Description"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Content Title"),
     *             @OA\Property(property="description", type="string", example="Updated Content Description"),
     *             @OA\Property(property="content", type="string", example="Updated Content Data")
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
     *         description="An error occurred while updating content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateContent(UploadContentRequest $request)
    {
        Gate::authorize('update', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->updateContent($request->id, $request->only('title', 'description', 'content'));
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }

    /**
     * Disable content.
     *
     * @param ContentReadRequest $request The request object containing content ID.
     * @return \Illuminate\Http\JsonResponse The response containing the disabled content.
     * @OA\Put(
     *     path="/api/content/disable",
     *     summary="Disable content",
     *     description="Endpoint to disable content.",
     *     operationId="disableContent",
     *     tags={"Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID of the content to be disabled",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content disabled",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Disabled Content Title"),
     *             @OA\Property(property="description", type="string", example="Disabled Content Description"),
     *             @OA\Property(property="content", type="string", example="Disabled Content Data")
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
     *         description="An error occurred while disabling content",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function disableContent(ContentReadRequest $request)
    {
        Gate::authorize('disable', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $content = $this->contentWriteService->disableContent($request->id);
            $this->cacheService->invalidateGroupCache('Content');
            return $content;
        });
    }

    /**
     * Update the order of content.
     *
     * @param UpdateContentOrderRequest $request The request object containing the new order of content.
     * @return \Illuminate\Http\JsonResponse The response containing the updated order of content.
     * @OA\Put(
     *     path="/api/content/updateContentOrder",
     *     summary="Update content order",
     *     description="Endpoint to update the order of content.",
     *     operationId="updateContentOrder",
     *     tags={"Content Management"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="order", type="array", @OA\Items(type="integer"), example={1: 1, 2: 2, 3 : 3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content order updated",
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
     *         description="An error occurred while updating content order",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function updateContentOrder(UpdateContentOrderRequest $request)
    {
        Gate::authorize('updateOrder', [CourseContent::class, $request->id]);
        return $this->handleServiceCall(function () use ($request) {
            $contents = $this->contentWriteService->updateContentOrder($request->order);
            $this->cacheService->invalidateGroupCache('Content');
            return $contents;
        });
    }
}
