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


    public function __construct(IContentPrivacyReadService $contentReadService, IContentPrivacyWriteService $contentWriteService) {
        $this->contentReadService = $contentReadService;
        $this->contentWriteService = $contentWriteService;
    }


    public function getPublicContent(getPublicContentRequest $request){
        Gate::authorize('getPublicContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $contents = $this -> contentReadService -> getContentPrivacy($request -> id,1 ,$request ->perPage, $request ->page);
            return $contents;
        });
        
    }



    public function makePublicAContent(getIdContentRequest $request){
        Gate::authorize('makePublicAContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this -> contentWriteService -> changeContentPrivacy($request -> id,1);
            return $content;
        });
    } 


}
