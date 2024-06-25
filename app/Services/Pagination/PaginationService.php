<?php

namespace App\Services\Pagination;

use App\Interfaces\Service\Pagination\IPaginationService;

class PaginationService implements IPaginationService
{

    /**
     * Paginates the given query with the specified per-page count and page number.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query to be paginated.
     * @param int $perPage The number of results per page.
     * @param int $page The page number to be displayed.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated query results.
     */
    public function paginate($query, int $perPage, int $page)
    {
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
