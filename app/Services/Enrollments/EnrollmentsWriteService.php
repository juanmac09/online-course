<?php

namespace App\Services\Enrollments;

use App\Interfaces\Repository\Enrollments\IEnrollmentsWriteRespository;
use App\Interfaces\Service\Enrollments\IEnrollmentsWriteService;

class EnrollmentsWriteService implements IEnrollmentsWriteService
{
    public $enrollmentsRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IEnrollmentsWriteRespository $enrollmentsRepository)
    {
        $this->enrollmentsRepository = $enrollmentsRepository;
    }

    /**
     * Enrolls a user in a course.
     *
     * @param int $userId The ID of the user to be enrolled.
     * @param int $courseId The ID of the course to be enrolled in.
     *
     * @return bool True if the user is successfully enrolled, false otherwise.
     */
    public function enrollInCourse(int $userId, int $courseId)
    {
        return $this->enrollmentsRepository->enrollInCourse($userId, $courseId);
    }

    /**
     * Unenrolls a user from a course.
     *
     * @param int $userId The ID of the user to be unenrolled.
     * @param int $courseId The ID of the course to be unenrolled from.
     *
     * @return bool True if the user is successfully unenrolled, false otherwise.
     */
    public function unEnrollInCourse(int $userId, int $courseId)
    {
        return $this->enrollmentsRepository->unEnrollInCourse($userId, $courseId);
    }
}
