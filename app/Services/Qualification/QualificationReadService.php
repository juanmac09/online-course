<?php

namespace App\Services\Qualification;

use App\Interfaces\Repository\Qualification\IQualificationReadRepository;
use App\Interfaces\Service\Qualification\IQualificationReadService;

class QualificationReadService implements IQualificationReadService
{
    public $qualificationRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(IQualificationReadRepository $qualificationRepository)
    {
        $this->qualificationRepository = $qualificationRepository;
    }


    /**
     * Get all qualifications associated with a specific course.
     *
     * @param int $course_id The ID of the course to retrieve qualifications for.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of qualifications associated with the specified course.
     */
    public function getAllQualificationsByCourse(int $course_id)
    {

        $qualifications = $this->qualificationRepository->getAllQualificationsByCourse($course_id);

        $averageQualification = $this->calculateAverageQualification($qualifications);


        $result = [
            'total' => round($averageQualification, 1),
            'qualification' => $qualifications,
            
        ];

        return $result;
    }

    /**
     * Calculate the average qualification from a collection of qualifications.
     *
     * @param \Illuminate\Support\Collection $qualifications
     * @return float The average qualification score.
     */
    private function calculateAverageQualification($qualifications)
    {
        $totalQualifications = $qualifications->sum('count');
        $averageQualification = $totalQualifications > 0 ? $qualifications->sum(function ($qualification) {
            return $qualification->qualification * $qualification->count;
        }) / $totalQualifications : 0;

        return $averageQualification;
    }
}
