<?php

namespace App\Interfaces\Repository\Enrollments;

use App\Models\User;

interface ISearchEnrollmentsRepository
{
    public function findEnrollmentsByKeywords(User $user,string $keyword,int $perPage, int $page);
    public function getPublicAndActiveEnrollments(User $user,int $perPage, int $page);
}
