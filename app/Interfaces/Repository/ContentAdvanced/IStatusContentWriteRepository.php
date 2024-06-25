<?php

namespace App\Interfaces\Repository\ContentAdvanced;

interface IStatusContentWriteRepository
{
    public function changeContentStatus(int $content_id, int $status);
}
