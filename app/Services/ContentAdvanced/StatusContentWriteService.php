<?php

namespace App\Services\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IStatusContentWriteRepository;
use App\Interfaces\Service\ContentAdvanced\IStatusContentWriteService;

class StatusContentWriteService implements IStatusContentWriteService
{
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IStatusContentWriteRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }
    
    /**
     * Changes the status of a content.
     *
     * @param int $content_id The ID of the content to change the status for.
     * @param int $status The new status to set for the content.
     *
     * @return bool True if the status was successfully changed, false otherwise.
     */
    public function changeContentStatus(int $content_id, int $status)
    {
        return $this->contentRepository->changeContentStatus($content_id, $status);
    }
}
