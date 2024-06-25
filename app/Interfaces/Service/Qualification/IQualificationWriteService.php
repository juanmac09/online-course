<?php

namespace App\Interfaces\Service\Qualification;

interface IQualificationWriteService
{
    public function createQualification(int $qualification, int $user_id, int $course_id);
    public function updateQualification(int $qualification, int $user_id, int $course_id);
}
