<?php

namespace App\Interfaces\Service\ContentAdvanced;

interface IStatusContentWriteService
{
    public function changeContentStatus(int $content_id, int $status);
}
