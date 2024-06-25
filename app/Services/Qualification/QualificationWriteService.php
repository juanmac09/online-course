<?php

namespace App\Services\Qualification;

use App\Interfaces\Repository\Qualification\IQualificationWriteRepository;
use App\Interfaces\Service\Qualification\IQualificationWriteService;

class QualificationWriteService implements IQualificationWriteService
{
    public $qualificationRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IQualificationWriteRepository $qualificationRepository)
    {
        $this->qualificationRepository = $qualificationRepository;
    }


    /**
     * Create a new qualification for a user in a course.
     *
     * @param int $qualification The qualification level.
     * @param int $user_id The ID of the user.
     * @param int $course_id The ID of the course.
     *
     * @return Quealification True if the qualification is successfully created, false otherwise.
     */
    public function createQualification(int $qualification, int $user_id, int $course_id)
    {
        return $this->qualificationRepository->createQualification($qualification, $user_id, $course_id);
    }

    /**
     * Update the qualification for a user in a course.
     *
     * @param int $qualification The new qualification level.
     * @param int $user_id The ID of the user.
     * @param int $course_id The ID of the course.
     *
     * @return bool True if the qualification is successfully updated, false otherwise.
     */
    public function updateQualification(int $qualification, int $user_id, int $course_id)
    {
        return $this->qualificationRepository->updateQualification($qualification, $user_id, $course_id);
    }
}
