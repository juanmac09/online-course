<?php

namespace App\Repository\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\ISearchContentRespository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\CourseContent;

class SearchContentRespository implements ISearchContentRespository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }
    /**
     * Get public and active content for a specific course.
     *
     * @param int $course_id The ID of the course to retrieve content for.
     * @param int $perPage The number of results to return per page.
     * @param int $page The page number of the results to return.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of public and active content for the specified course.
     */
    public function getPublicAndActiveContent(int $course_id, int $perPage, int $page)
    {
        $content = CourseContent::where('course_id', $course_id)->where('public', 1)->where('status', 1) -> orderBy('order','asc');
        return $this->paginationRepository->paginate($content, $perPage, $page);
    }
}
