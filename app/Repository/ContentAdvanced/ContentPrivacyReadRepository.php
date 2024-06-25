<?php

namespace App\Repository\ContentAdvanced;

use App\Interfaces\Repository\ContentAdvanced\IContentPrivacyReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\CourseContent;

class ContentPrivacyReadRepository implements IContentPrivacyReadRepository
{

    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }


    /**
     * Retrieves the contents of a course based on the specified privacy level.
     *
     * @param int $course_id The ID of the course to retrieve contents for.
     * @param int $privacy The privacy level of the contents to retrieve.
     * @param int $perPage The number of contents to retrieve per page.
     * @param int $page The page number of the contents to retrieve.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated collection of the course contents based on the specified privacy level.
     */
    public function getContentPrivacy(int $course_id, int $privacy, int $perPage, int $page): \Illuminate\Pagination\LengthAwarePaginator
    {
        $contents = CourseContent::where('course_id', $course_id)->where('public', $privacy);
        return $this->paginationRepository->paginate($contents, $perPage, $page);
    }
}
