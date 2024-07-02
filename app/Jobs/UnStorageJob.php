<?php

namespace App\Jobs;

use App\Interfaces\Service\FileStorage\IUnStorageService;
use App\Models\CourseContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnStorageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $oldPath;
    public $unStorageService;
    /**
     * Create a new job instance.
     */
    public function __construct(string $oldPath)
    {
        $this -> unStorageService = app(IUnStorageService::class);
        $this-> oldPath = $oldPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $this -> unStorageService -> unStorage($this -> oldPath);
    }
}
