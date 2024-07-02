<?php

namespace App\Services\FileStorage;

use Illuminate\Support\Facades\Storage;

use App\Interfaces\Service\FileStorage\IUnStorageService;

class UnStorageService implements IUnStorageService
{
    /**
     * Deletes a file from the S3 storage.
     *
     * @param string $path The path of the file to be deleted.
     *
     * @return bool|null True if the file is successfully deleted, null otherwise.
     */
    public function unStorage(string $path)
    {
        $file = Storage::disk('s3')->delete($path);
        return $file;
    }
}
