<?php

namespace App\Interfaces\Service\CourseAdvanced;

interface ISearchPublicCourseService
{
    public function findByKeyword(string $keyword, int $perPage, int $page);
    public function getPublicAndActiveCourses(int $user_id,int $perPage, int $page);
}
