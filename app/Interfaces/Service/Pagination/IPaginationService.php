<?php

namespace App\Interfaces\Service\Pagination;

interface IPaginationService
{
    public function paginate($query, int $perPage, int $page);
}
