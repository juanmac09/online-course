<?php

namespace App\Interfaces\Service\FileStorage;

interface IStorageService
{
    public function storage(int $course_id,mixed $content);
}
