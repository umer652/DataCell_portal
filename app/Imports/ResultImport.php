<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\OfferedCourse;
use App\Models\Enrollment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ResultImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // skip header
            if ($index == 0) continue;

            $rollNo = $row[0];
            $courseCode = $row[1];
            $grade = $row[2];

            $student = Student::where('roll_no', $rollNo)->first();
            if (!$student) continue;

            $offeredCourse = OfferedCourse::whereHas('course', function ($q) use ($courseCode) {
                $q->where('course_code', $courseCode);
            })->first();

            if (!$offeredCourse) continue;

            Enrollment::where('student_id', $student->id)
                ->where('offered_courses_id', $offeredCourse->id)
                ->update([
                    'grade' => $grade
                ]);
        }
    }
}