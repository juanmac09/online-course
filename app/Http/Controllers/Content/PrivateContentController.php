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

    public function __construct(IContentPrivacyReadService $contentReadService, IContentPrivacyWriteService $contentWriteService) {
        $this->contentReadService = $contentReadService;
        $this->contentWriteService = $contentWriteService;
    }


    public function getPrivateContent(getPublicContentRequest $request){
        Gate::authorize('getPrivateContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this -> contentReadService -> getContentPrivacy($request -> id,0 ,$request ->perPage, $request ->page);
            return $content;
        });
        
    }


    public function makePrivateAContent(getIdContentRequest $request){
        Gate::authorize('makePrivateAContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this -> contentWriteService -> changeContentPrivacy($request -> id,0);
            return $content; 
        });
       
    }
}
