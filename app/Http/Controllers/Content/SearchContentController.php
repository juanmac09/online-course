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

    public function __construct(ISearchContentService $courseService) {
        parent::__construct();
        $this->courseService = $courseService;
    }

    
    public function getPublicAndActiveContent(getPublicContentRequest $request){
        Gate::authorize('getPublicAndActiveContent', [CourseContent::class, $request -> id]);
        return $this -> handleServiceCall(function () use ($request){
            $content = $this ->cacheService -> storeInCache('Content','PublicAndActiveContent',$request->perPage,$request -> page,function () use ($request){
                return $this -> courseService -> getPublicAndActiveContent($request -> id,$request ->perPage, $request ->page);
            },10);
            return $content;
        });
    }
}
