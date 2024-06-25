<?php

namespace App\Interfaces\Repository\Content;

interface IContentWriteRepository
{
    public function uploadContent(array $contentData);
    public function updateContent(int $id, array $contentData);
    public function disableContent(int $id);
    public function updateContentOrder(int $id, int $position);
}
