<?php

namespace App\Interfaces\Service\Enrollments;

interface ISearchEnrollmentsService
{
    public function findEnrollmentsByKeywords(int $userId, string $keyword, int $perPage, int $page);
    public function getPublicAndActiveEnrollments(int $userId, int $perPage, int $page);
}
