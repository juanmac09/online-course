<?php

namespace App\Repository\User;

use App\Interfaces\Repository\User\IUserReadRepository;
use App\Interfaces\Service\Pagination\IPaginationService;
use App\Models\User;

class UserReadRepository implements IUserReadRepository
{
    public $paginationRepository;

    public function __construct(IPaginationService $paginationRepository)
    {
        $this->paginationRepository = $paginationRepository;
    }
    /**
     * Get all users from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers(int $perPage, int $page)
    {
        $users = User::where('status', 1);
        return $this->paginationRepository->paginate($users, $perPage, $page);
    }
}
