<?php

namespace App\Services\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyWriteRepository;
use App\Interfaces\Service\ContentAdvanced\IContentPrivacyWriteService;

class ContentPrivacyWriteService implements IContentPrivacyWriteService
{
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IContentPrivacyWriteRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * Changes the privacy of a content.
     *
     * @param int $content_id The unique identifier of the content.
     * @param int $privacy The new privacy level for the content.
     *
     * @return bool True if the privacy was successfully changed, false otherwise.
     */
    public function changeContentPrivacy(int $content_id, int $privacy)
    {
        return $this->contentRepository->changeContentPrivacy($content_id, $privacy);
    }
}
