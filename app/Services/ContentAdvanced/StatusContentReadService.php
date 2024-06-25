<?php

namespace App\Services\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IStatusContentReadRepository;
use App\Interfaces\Service\ContentAdvanced\IStatusContentReadService;

class StatusContentReadService implements IStatusContentReadService
{

    public $contentRespository;
    /**
     * Create a new class instance.
     */
    public function __construct(IStatusContentReadRepository $contentRespository)
    {
        $this->contentRespository = $contentRespository;
    }

    /**
     * Retrieves the content status based on the provided parameters.
     *
     * @param int $course_id The ID of the course to retrieve the content status for.
     * @param int $status The status of the content to retrieve.
     * @param int $perPage The number of results to return per page.
     * @param int $page The page number of the results to return.
     *
     * @return array An array of content statuses matching the provided parameters.
     */
    public function getContentStatus(int $course_id, int $status, int $perPage, int $page)
    {
        return $this->contentRespository->getContentStatus($course_id, $status, $perPage, $page);
    }
}
