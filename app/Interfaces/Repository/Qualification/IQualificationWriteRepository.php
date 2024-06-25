<?php

namespace App\Interfaces\Repository\Qualification;

interface IQualificationWriteRepository
{
    public function createQualification(int $qualification,int $user_id,int $course_id);
    public function updateQualification(int $qualification, int $user_id, int $course_id);
}
