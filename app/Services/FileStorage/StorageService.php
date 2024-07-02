<?php

namespace App\Services\FileStorage;

use App\Interfaces\Service\FileStorage\IStorageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService implements IStorageService
{


    public function storage(int $course_id, mixed $content)
    { 
        $uuid = $this -> generateUuid();
        $path = $this->generatePath($course_id);
        $extension = $this -> getExtension($content -> getClientOriginalName());
        return Storage::disk('s3')->put($path, $content, $uuid.'.'.$extension);
    }


    /**
     * Generate a unique path for storing content related to a specific course.
     *
     * @param int $course_id The ID of the course for which the content is being stored.
     *
     * @return string A unique path in the format "course_<course_id>".
     */
    private function generatePath(int $course_id): string
    {
        return 'course_' . $course_id;
    }


    /**
     * Generate a unique UUID.
     *
     * This method generates a unique UUID (Universally Unique Identifier) using Laravel's Str::uuid() helper function.
     *
     * @return string A unique UUID in the format "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".
     */
    private function generateUuid(): string
    {
        return Str::uuid();
    }


    /**
     * Get the file extension from the filename.
     *
     * This method extracts the file extension from the given filename using PHP's pathinfo() function.
     *
     * @param string $filename The name of the file from which the extension needs to be extracted.
     *
     * @return string The file extension in lowercase.
     */
    private function getExtension(string $filename): string {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}
