<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class TranscriptController extends Controller
{
    public function index()
    {
        $students = Student::with('program')->get();
        return view('Admin.transcript-list', compact('students'));
    }
    
    public function show($id)
    {
        // Find student by ID
        $student = Student::with('program')->findOrFail($id);
        
        // Get enrollments with course data - ONLY WHERE GRADE IS NOT NULL AND NOT EMPTY
        $enrollments = DB::table('enrollment')
            ->join('offered_courses', 'enrollment.offered_course_id', '=', 'offered_courses.id')
            ->join('course', 'offered_courses.course_id', '=', 'course.id')
            ->leftJoin('program_course_scheme', function($join) use ($student) {
                $join->on('program_course_scheme.course_id', '=', 'course.id')
                     ->where('program_course_scheme.program_id', '=', $student->program_id);
            })
            ->where('enrollment.student_id', $student->id)
            ->whereNotNull('enrollment.grade')  // Only where grade exists
            ->where('enrollment.grade', '!=', '')  // Grade is not empty
            ->select(
                'enrollment.*',
                'course.course_code',
                'course.course_title as course_name',
                DB::raw('COALESCE(program_course_scheme.credit_hrs, 3) as credits')
            )
            ->distinct()
            ->orderBy('enrollment.semester')
            ->get();
        
        // Check if any enrollments with grades exist
        $hasGrades = $enrollments->isNotEmpty();
        
        // If no grades found, show message instead of transcript
        if (!$hasGrades) {
            return view('Admin.transcript-no-grades', compact('student'));
        }
        
        // Group by semester
        $groupedEnrollments = [];
        foreach ($enrollments as $enrollment) {
            $semesterKey = 'Semester ' . $enrollment->semester;
            if (!isset($groupedEnrollments[$semesterKey])) {
                $groupedEnrollments[$semesterKey] = [];
            }
            $groupedEnrollments[$semesterKey][] = $enrollment;
        }
        
        // Calculate GPA for each semester
        $semesterData = [];
        foreach ($groupedEnrollments as $semester => $enrollmentsGroup) {
            $semesterData[$semester] = [
                'enrollments' => $enrollmentsGroup,
                'gpa' => $this->calculateGPAFromCollection($enrollmentsGroup),
                'total_credits' => collect($enrollmentsGroup)->sum('credits')
            ];
        }
        
        // Calculate CGPA
        $cgpa = $this->calculateGPAFromCollection($enrollments);
        
        return view('Admin.transcript', compact('student', 'semesterData', 'cgpa'));
    }
    
    public function downloadPDF($id)
    {
        $student = Student::with('program')->findOrFail($id);
        
        $enrollments = DB::table('enrollment')
            ->join('offered_courses', 'enrollment.offered_course_id', '=', 'offered_courses.id')
            ->join('course', 'offered_courses.course_id', '=', 'course.id')
            ->leftJoin('program_course_scheme', function($join) use ($student) {
                $join->on('program_course_scheme.course_id', '=', 'course.id')
                     ->where('program_course_scheme.program_id', '=', $student->program_id);
            })
            ->where('enrollment.student_id', $student->id)
            ->whereNotNull('enrollment.grade')
            ->where('enrollment.grade', '!=', '')
            ->select(
                'enrollment.*',
                'course.course_code',
                'course.course_title as course_name',
                DB::raw('COALESCE(program_course_scheme.credit_hrs, 3) as credits')
            )
            ->distinct()
            ->orderBy('enrollment.semester')
            ->get();
        
        if ($enrollments->isEmpty()) {
            return redirect()->route('transcripts.index')
                ->with('warning', 'No grades available for this student yet. Transcript cannot be generated.');
        }
        
        $groupedEnrollments = [];
        foreach ($enrollments as $enrollment) {
            $semesterKey = 'Semester ' . $enrollment->semester;
            if (!isset($groupedEnrollments[$semesterKey])) {
                $groupedEnrollments[$semesterKey] = [];
            }
            $groupedEnrollments[$semesterKey][] = $enrollment;
        }
        
        $semesterData = [];
        foreach ($groupedEnrollments as $semester => $enrollmentsGroup) {
            $semesterData[$semester] = [
                'enrollments' => $enrollmentsGroup,
                'gpa' => $this->calculateGPAFromCollection($enrollmentsGroup),
                'total_credits' => collect($enrollmentsGroup)->sum('credits')
            ];
        }
        
        $cgpa = $this->calculateGPAFromCollection($enrollments);
        
        $pdf = PDF::loadView('Admin.transcript-pdf', compact('student', 'semesterData', 'cgpa'));
        return $pdf->download("transcript_{$student->roll_no}.pdf");
    }
    
    public function print($id)
    {
        $student = Student::with('program')->findOrFail($id);
        
        $enrollments = DB::table('enrollment')
            ->join('offered_courses', 'enrollment.offered_course_id', '=', 'offered_courses.id')
            ->join('course', 'offered_courses.course_id', '=', 'course.id')
            ->leftJoin('program_course_scheme', function($join) use ($student) {
                $join->on('program_course_scheme.course_id', '=', 'course.id')
                     ->where('program_course_scheme.program_id', '=', $student->program_id);
            })
            ->where('enrollment.student_id', $student->id)
            ->whereNotNull('enrollment.grade')
            ->where('enrollment.grade', '!=', '')
            ->select(
                'enrollment.*',
                'course.course_code',
                'course.course_title as course_name',
                DB::raw('COALESCE(program_course_scheme.credit_hrs, 3) as credits')
            )
            ->distinct()
            ->orderBy('enrollment.semester')
            ->get();
        
        if ($enrollments->isEmpty()) {
            return redirect()->route('transcripts.index')
                ->with('warning', 'No grades available for this student yet. Transcript cannot be generated.');
        }
        
        $groupedEnrollments = [];
        foreach ($enrollments as $enrollment) {
            $semesterKey = 'Semester ' . $enrollment->semester;
            if (!isset($groupedEnrollments[$semesterKey])) {
                $groupedEnrollments[$semesterKey] = [];
            }
            $groupedEnrollments[$semesterKey][] = $enrollment;
        }
        
        $semesterData = [];
        foreach ($groupedEnrollments as $semester => $enrollmentsGroup) {
            $semesterData[$semester] = [
                'enrollments' => $enrollmentsGroup,
                'gpa' => $this->calculateGPAFromCollection($enrollmentsGroup),
                'total_credits' => collect($enrollmentsGroup)->sum('credits')
            ];
        }
        
        $cgpa = $this->calculateGPAFromCollection($enrollments);
        
        return view('Admin.transcript-print', compact('student', 'semesterData', 'cgpa'));
    }
    
    private function calculateGPAFromCollection($enrollments)
    {
        $totalPoints = 0;
        $totalCredits = 0;
        
        foreach ($enrollments as $enrollment) {
            $gradePoints = $this->getGradePoints($enrollment->grade);
            $credits = $enrollment->credits ?? 3;
            
            $totalPoints += $gradePoints * $credits;
            $totalCredits += $credits;
        }
        
        return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
    }
    
    private function getGradePoints($grade)
    {
        if (!$grade) return 0.0;
        
        $gradePoints = [
            'A+' => 4.0, 'A' => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D+' => 1.3, 'D' => 1.0, 'F' => 0.0,
        ];
        
        if (is_numeric($grade)) {
            if ($grade >= 90) return 4.0;
            if ($grade >= 85) return 3.7;
            if ($grade >= 80) return 3.3;
            if ($grade >= 75) return 3.0;
            if ($grade >= 70) return 2.7;
            if ($grade >= 65) return 2.3;
            if ($grade >= 60) return 2.0;
            if ($grade >= 55) return 1.7;
            if ($grade >= 50) return 1.0;
            return 0.0;
        }
        
        return $gradePoints[strtoupper(trim($grade))] ?? 0.0;
    }
}