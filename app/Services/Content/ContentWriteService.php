<?php

namespace App\Services\Content;

use App\Interfaces\Repository\Content\IContentWriteRepository;
use App\Interfaces\Service\Content\IContentWriteService;

class ContentWriteService implements IContentWriteService
{
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IContentWriteRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }


    /**
     * Uploads the provided content data to the repository.
     *
     * @param array $contentData The content data to be uploaded.
     * @return mixed The uploaded content data.
     *
     * @throws \InvalidArgumentException If the content data is invalid.
     */
    public function uploadContent(array $contentData)
    {
        $contentData['content'] = "http://localhost/example";
        $content = $this->contentRepository->uploadContent($contentData);
        return $content;
    }

    /**
     * Updates the provided content data in the repository with the specified id.
     *
     * @param int $id The unique identifier of the content to be updated.
     * @param array $contentData The updated content data.
     * @return mixed The updated content data.
     *
     * @throws \InvalidArgumentException If the content data is invalid.
     */
    public function updateContent(int $id, array $contentData)
    {
        if (isset($contentData['content'])) {
            $contentData['content'] = "http://localhost/newExample";
        }

        $content = $this->contentRepository->updateContent($id, $contentData);
        return $content;
    }

    /**
     * Disables the content with the specified id in the repository.
     *
     * @param int $id The unique identifier of the content to be disabled.
     * @return mixed The disabled content data.
     */
    public function disableContent(int $id)
    {
        return $this->contentRepository->disableContent($id);
    }


    /**
     * Updates the order of the provided content data in the repository.
     *
     * @param array $contentData An associative array where the keys are the content IDs and the values are the new positions.
     * @return \Illuminate\Support\Collection A collection of updated content data.
     */
    public function updateContentOrder(array $contentData)
    {
        $contents = collect();
        foreach ($contentData as $id => $position) {
            $content = $this->contentRepository->updateContentOrder($id, $position);
            $contents->push($content);
        }
        return $contents;
    }
}
