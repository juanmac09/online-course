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

    public function __construct(IContentWriteService $contentWriteService, IContentReadService $contentReadService) {
        $this->contentWriteService = $contentWriteService;
        $this -> contentReadService = $contentReadService;
    }

    public function uploadContent(UploadContentRequest $request){

        Gate::authorize('create', [CourseContent::class, $request -> course_id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this->contentWriteService->uploadContent($request->only('title', 'description', 'content','course_id'));
            return $content;
        });
    }

    public function getContentForCourse(ContentReadRequest $request){
        Gate::authorize('getContentForCourse', [CourseContent::class, $request ->id]);
        return $this -> handleServiceCall(function () use ($request){
            $contents = $this->contentReadService->getContentForCourse($request->id,$request -> perPage, $request -> page);
            return $contents;
        });
    }


    public function updateContent(UploadContentRequest $request){
        Gate::authorize('update', [CourseContent::class, $request ->id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this->contentWriteService->updateContent($request -> id,$request->only('title', 'description', 'content'));
            return $content;
        });
           
    }


    public function disableContent(ContentReadRequest $request){
        Gate::authorize('disable', [CourseContent::class, $request ->id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this->contentWriteService->disableContent($request->id);
            return $content;
        });
    }


    public function updateContentOrder(UpdateContentOrderRequest $request){
        Gate::authorize('updateOrder', [CourseContent::class, $request ->id]);
        return $this -> handleServiceCall(function () use ($request){
            $contents = $this->contentWriteService->updateContentOrder($request->order);
            return $contents;
        });  
    }

}
