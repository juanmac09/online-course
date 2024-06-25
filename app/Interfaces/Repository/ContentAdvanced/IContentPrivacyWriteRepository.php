<?php

namespace App\Interfaces\Repository\ContentAdvanced;

interface IContentPrivacyWriteRepository
{
    public function changeContentPrivacy(int $content_id, int $privacy);
}
