<?php

namespace App\Repository\Qualification;

use App\Interfaces\Repository\Qualification\IQualificationWriteRepository;
use App\Models\Qualification;

class QualificationWriteRepository implements IQualificationWriteRepository
{

    /**
     * Create a new qualification record.
     *
     * @param int $qualification The qualification score.
     * @param int $user_id The ID of the user who earned the qualification.
     * @param int $course_id The ID of the course for which the qualification is earned.
     *
     * @return Qualification The newly created qualification record.
     */

    public function createQualification(int $qualification, int $user_id, int $course_id)
    {
        $points = Qualification::create([
            'qualification' => $qualification,
            'user_id' => $user_id,
            'course_id' => $course_id
        ]);
        return $points;
    }


    /**
     * Updates an existing qualification record.
     *
     * @param int $qualification The new qualification score.
     * @param int $user_id The ID of the user who earned the qualification.
     * @param int $course_id The ID of the course for which the qualification is earned.
     *
     * @return Qualification The updated qualification record.
     *
     * @throws \Exception If no qualification record is found for the given user and course.
     */
    public function updateQualification(int $qualification, int $user_id, int $course_id)
    {
        $point = Qualification::where('user_id', $user_id)->where('course_id', $course_id)->first();
        $point->update([
            'qualification' => $qualification,
        ]);
        return $point;
    }
}
