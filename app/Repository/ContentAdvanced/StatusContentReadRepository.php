<?php

namespace App\Repository\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IStatusContentReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\CourseContent;

class StatusContentReadRepository implements IStatusContentReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }

    /**
     * Get the contents of a course based on the provided course id and status.
     *
     * @param int $course_id The id of the course to retrieve contents for.
     * @param int $status The status of the contents to retrieve.
     * @param int $perPage The number of contents to retrieve per page.
     * @param int $page The page number of the contents to retrieve.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of contents matching the provided course id and status.
     */
    public function getContentStatus(int $course_id, int $status, int $perPage, int $page)
    {
        $contents = CourseContent::where('course_id', $course_id)->where('status', $status);
        return $this->paginationRepository->paginate($contents, $perPage, $page);
    }
}
