<?php

namespace App\Interfaces\Repository\CourseAdvanced;

interface ISearchPublicCourseRepository
{
    public function findByKeyword(string $keyword,int $perPage, int $page);
    public function getPublicAndActiveCourses(int $user_id,int $perPage, int $page);
}
