<?php

namespace App\Services\Content;

use App\Interfaces\Repository\Content\IContentRepository;
use App\Interfaces\Repository\Content\IContentWriteRepository;
use App\Interfaces\Service\Content\IContentWriteService;
use App\Interfaces\Service\FileStorage\IStorageService;
use App\Jobs\UnStorageJob;

class ContentWriteService implements IContentWriteService
{
    public $contentWriteRepository;
    public $storageService;
    public $contentRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IContentWriteRepository $contentWriteRepository,IStorageService $storageService, IContentRepository $contentRepository)
    {
        $this->contentWriteRepository = $contentWriteRepository;
        $this->storageService = $storageService;
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
        $path =  $this -> storageService -> storage($contentData['course_id'],$contentData['content']);
        $contentData['content'] = $path;
        $content = $this->contentWriteRepository->uploadContent($contentData);
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
        $content = null;
        if (isset($contentData['content'])) {
            $content = $this -> contentRepository -> contentById($id);
            $path = $this -> storageService -> storage($content -> course_id,$contentData['content']);
            $contentData['content'] = $path;
            UnStorageJob::dispatch($content -> getRawOriginal('content'));
            $content = null;
        }

        $content = $this->contentWriteRepository->updateContent($id, $contentData);
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
        return $this->contentWriteRepository->disableContent($id);
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
            $content = $this->contentWriteRepository->updateContentOrder($id, $position);
            $contents->push($content);
        }
        return $contents;
    }
}
