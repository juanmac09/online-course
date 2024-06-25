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

    public function __construct(IStatusContentReadService $contentReadService, IStatusContentWriteService $contentWriteService) {
        $this->contentReadService = $contentReadService;
        $this->contentWriteService = $contentWriteService;
    }



    public function getStatusDesactiveContent(getPublicContentRequest $request){
        Gate::authorize('getStatusDesactiveContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this -> contentReadService -> getContentStatus($request -> id,0 ,$request ->perPage, $request ->page);
            return $content;
        });
    }



    public function changeStatusActiveContent(getIdContentRequest $request){
        Gate::authorize('changeStatusActiveContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this -> contentWriteService -> changeContentStatus($request -> id,1);
            return $content;
        });
            
    }
}