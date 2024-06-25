<?php

namespace App\Services\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\ISearchContentRespository;
use App\Interfaces\Service\ContentAdvanced\ISearchContentService;

class SearchContentService implements ISearchContentService
{
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ISearchContentRespository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }
    

    /**
     * Retrieves public and active content for a given course.
     *
     * @param int $course_id The ID of the course to retrieve content for.
     * @param int $perPage The number of items to return per page.
     * @param int $page The page number to retrieve.
     *
     * @return array An array of public and active content items for the specified course.
     */
    public function getPublicAndActiveContent(int $course_id, int $perPage, int $page)
    {
        return $this->contentRepository->getPublicAndActiveContent($course_id, $perPage, $page);
    }
}
