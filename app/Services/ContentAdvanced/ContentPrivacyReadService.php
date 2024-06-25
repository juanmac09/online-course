<?php

namespace App\Services\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyReadRepository;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyReadService;

class ContentPrivacyReadService implements IContentPrivacyReadService
{
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IContentPrivacyReadRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }


    /**
     * Retrieves content privacy data based on the provided parameters.
     *
     * @param int $course_id The ID of the course to retrieve content privacy for.
     * @param int $privacy The privacy level to filter the content by.
     * @param int $perPage The number of results to return per page.
     * @param int $page The page number to retrieve.
     *
     * @return array An array of content privacy data matching the provided parameters.
     */
    public function getContentPrivacy(int $course_id, int $privacy, int $perPage, int $page)
    {
        return $this->contentRepository->getContentPrivacy($course_id,  $privacy,  $perPage,  $page);
    }
}
