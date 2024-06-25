<?php

namespace App\Repository\Qualification;

use App\Interfaces\Repository\Qualification\IQualificationReadRepository;
use Illuminate\Support\Facades\DB;

class QualificationReadRepository implements IQualificationReadRepository
{
    /**
     * Get all qualifications by course id.
     *
     * @param int $course_id The id of the course to get qualifications for.
     *
     * @return \Illuminate\Support\Collection A collection of qualifications for the given course.
     *         Each element in the collection is an associative array with keys 'qualification' and 'count'.
     */
    public function getAllQualificationsByCourse(int $course_id)
    {
        $qualifications = DB::table('qualifications as q')
            ->rightJoin(DB::raw('(SELECT 1 as qualification UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) as all_qualifications'), function ($join) use ($course_id) {
                $join->on('q.qualification', '=', 'all_qualifications.qualification')
                    ->where('q.course_id', '=', $course_id);
            })
            ->select('all_qualifications.qualification', DB::raw('COALESCE(COUNT(q.id), 0) as count'))
            ->groupBy('all_qualifications.qualification')
            ->orderBy('all_qualifications.qualification')
            ->get();


        return $qualifications;
    }
}
