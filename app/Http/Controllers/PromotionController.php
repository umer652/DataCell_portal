<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\OfferedCourse;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function promote()
    {
        DB::beginTransaction();

        try {

            // 1. PASS students find
            $passStudents = Enrollment::where('grade', '!=', 'F')
                ->select('student_id')
                ->distinct()
                ->get();

            foreach ($passStudents as $row) {

                $student = Student::find($row->student_id);

                if (!$student) continue;

                // 2. next semester
                $nextSemester = $student->semester + 1;

                // 3. update student semester
                $student->semester = $nextSemester;
                $student->save();

                // 4. get next semester courses
                $courses = OfferedCourse::where('program_id', $student->program_id)
                    ->where('semester', $nextSemester)
                    ->get();

                // 5. create new enrollments
                foreach ($courses as $course) {

                    Enrollment::create([
                        'student_id' => $student->id,
                        'program_id' => $student->program_id,
                        'session_id' => $student->session_id,
                        'section_id' => $student->section_id,
                        'offered_courses_id' => $course->id,
                        'semester' => $nextSemester,
                        'enrollment_date' => now(),
                        'grade' => null
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Students promoted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}