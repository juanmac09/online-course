<?php

namespace App\Interfaces\Service\Course;

use App\Models\User;

interface ICourseOwnerService
{
    public function isOwner(User $user, int $courseId);
    public function isSuscriber(User $user,int $courseId);
}
