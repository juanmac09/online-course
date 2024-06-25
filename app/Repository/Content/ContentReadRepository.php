<?php

namespace App\Repository\Content;

use App\Interfaces\Repository\Content\IContentReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\CourseContent;

class ContentReadRepository implements IContentReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Retrieves the content for a specific course.
     *
     * @param int $id The ID of the course.
     * @param int $perPage The number of items to display per page.
     * @param int $page The page number to retrieve.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of course contents.
     */
    public function getContentForCourse(int $id, int $perPage, int $page): \Illuminate\Pagination\LengthAwarePaginator
    {
        $contents = CourseContent::where('course_id', $id)
            ->where('status', 1)
            ->where('public', 1);
        return $this->paginationRepository->paginate($contents, $perPage, $page);
    }
}
