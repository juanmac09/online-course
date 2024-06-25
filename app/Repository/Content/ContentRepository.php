<?php

namespace App\Repository\Content;

use App\Interfaces\Repository\Content\IContentRepository;
use App\Models\CourseContent;

class ContentRepository implements IContentRepository
{
    /**
     * Retrieves a content item by its id.
     *
     * @param int $id The unique identifier of the content item.
     *
     * @return \App\Models\CourseContent|null The content item if found, otherwise null.
     */
    public function contentById(int $id)
    {
        $content = CourseContent::find($id);
        return $content;
    }
}
