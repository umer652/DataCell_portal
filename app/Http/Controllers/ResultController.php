<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ProgramSemester;
use App\Imports\ResultImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('Admin.results.index', compact('students'));
    }

    public function show($id)
    {
        $student = Student::with([
            'enrollments.offeredCourse.course'
        ])->find($id);

        if (!$student) {
            abort(404);
        }

        $programSemester = ProgramSemester::where('program_id', $student->program_id)->first();

        $minSemester = $programSemester ? $programSemester->min_semester : 0;
        $maxSemester = $programSemester ? $programSemester->max_semester : 0;
        $totalSemesters = $maxSemester - $minSemester + 1;

        $currentSemester = $student->enrollments->max('semester');

        $passedGrades = ['A', 'A+', 'A-', 'B+', 'B'];

        // CURRENT SEMESTER
        $currentEnrollments = $student->enrollments
            ->where('semester', $currentSemester)
            ->map(function ($enroll) use ($passedGrades) {
                $enroll->result_status = in_array($enroll->grade, $passedGrades)
                    ? 'pass'
                    : 'fail';
                return $enroll;
            });

        $passedGrades = ['A', 'A+', 'A-', 'B+', 'B'];

        $previousEnrollments = $student->enrollments
            ->where('semester', '<', $currentSemester)
            ->map(function ($enroll) use ($passedGrades) {
                $enroll->result_status = in_array($enroll->grade, $passedGrades)
                    ? 'pass'
                    : 'fail';
                return $enroll;
            })
            ->groupBy('semester');

        // STATS
        $totalCourses = $student->enrollments->count();

        $passed = $student->enrollments
            ->whereIn('grade', ['A', 'A+', 'A-', 'B+', 'B'])
            ->count();

        $failed = $totalCourses - $passed;

        $currentGpa = 0;
        $count = $currentEnrollments->count();

        if ($count > 0) {
            $totalPoints = $currentEnrollments->sum(function ($e) {
                return $this->getGradePoint($e->grade);
            });

            $currentGpa = $totalPoints / $count;
        }

        $cgpa = 0;
        $total = $student->enrollments->count();

        if ($total > 0) {
            $totalPoints = $student->enrollments->sum(function ($e) {
                return $this->getGradePoint($e->grade);
            });

            $cgpa = $totalPoints / $total;
        }

        // ✅ RETURN
        return view('Admin.results.show', compact(
            'student',
            'currentEnrollments',
            'previousEnrollments',
            'totalCourses',
            'passed',
            'failed',
            'totalSemesters',
            'minSemester',
            'maxSemester',
            'currentGpa',
            'cgpa',
            'currentSemester'
        ));
    }

    function getGradePoint($grade)
    {
        return match ($grade) {
            'A+' => 4.0,
            'A'  => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B'  => 3.0,
            'C'  => 2.0,
            default => 0
        };
    }

    public function promoteStudents()
    {
        $students = Student::with('enrollments')->get();

        foreach ($students as $student) {

            $enrollments = $student->enrollments;

            if ($enrollments->count() == 0) {
                continue;
            }

            $currentSemester = $student->semester;

            $currentEnrollments = $enrollments->where('semester', $currentSemester);

            $passedGrades = ['A', 'A+', 'A-', 'B+', 'B', 'C', 'D'];

            $failedCount = $currentEnrollments->whereNotIn('grade', $passedGrades)->count();

            if ($failedCount == 0 && $currentEnrollments->count() > 0) {

                $student->enrollments()
                    ->where('semester', $currentSemester)
                    ->update([
                        'status' => 'completed'
                    ]);

                $student->semester = $currentSemester + 1;
                $student->status = 'promoted';
                $student->save();
            } else {

                $student->status = 'not_promoted';
                $student->save();
            }
        }

        return redirect()->back()->with('success', 'Promotion completed successfully!');
    }

    public function uploadResult(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ResultImport, $request->file('file'));

        return back()->with('success', 'Result uploaded successfully!');
    }
}
