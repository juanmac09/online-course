<?php

namespace App\Services\Content;

use App\Interfaces\Repository\Content\IContentReadRepository;
use App\Interfaces\Service\Content\IContentReadService;

class ContentReadService implements IContentReadService
{

    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IContentReadRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    
    /**
     * Retrieves the content for a specific course.
     *
     * @param int $id The ID of the course for which to retrieve the content.
     *
     * @return array The content for the specified course.
     */
    public function getContentForCourse(int $id,int $perPage, int $page)
    {
        $contents = $this->contentRepository->getContentForCourse($id, $perPage,  $page);
        return $contents;
    }
}
