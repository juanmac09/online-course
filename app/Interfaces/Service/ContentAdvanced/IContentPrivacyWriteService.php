<?php

namespace App\Interfaces\Service\ContentAdvanced;

interface IContentPrivacyWriteService
{
    public function changeContentPrivacy(int $content_id, int $privacy);
}
