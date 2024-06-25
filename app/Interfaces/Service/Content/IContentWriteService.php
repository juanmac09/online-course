<?php

namespace App\Interfaces\Service\Content;

interface IContentWriteService
{
    public function uploadContent(array $contentData);
    public function updateContent(int $id,array $contentData);
    public function disableContent(int $id);
    public function updateContentOrder(array $contentData);
}
