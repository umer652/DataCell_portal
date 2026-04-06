<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Session;
use App\Models\Section;
use App\Models\OfferedCourse;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
public function index(Request $request)
{
    $students = Student::all();
    $programs = Program::all();
    $sessions = Session::all();
    $sections = Section::all();

    $query = Enrollment::with([
        'student',
        'offeredCourse.course',
        'program',
        'session',
        'section'
    ]);

    // 🔍 SEARCH
    if ($request->search) {
        $query->whereHas('student', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    // 🎯 SESSION FILTER
    if ($request->session_id) {
        $query->where('session_id', $request->session_id);
    }

    // 🎯 SEMESTER FILTER
    if ($request->semester) {
        $query->where('semester', $request->semester);
    }

    // 🎯 PROGRAM FILTER
    if ($request->program_id) {
        $query->where('program_id', $request->program_id);
    }

    $enrollments = $query->get();

    // AJAX RESPONSE
    if ($request->ajax()) {

        $html = '';

        foreach ($enrollments as $e) {
            $html .= '
            <tr>
                <td>' . ($e->student->name ?? '-') . '</td>
                <td>' . ($e->offeredCourse->course->course_title ?? '-') . '</td>
                <td>' . ($e->program->name ?? '-') . '</td>
                <td>' . ($e->session->name ?? '-') . '</td>
                <td>' . ($e->semester ?? '-') . '</td>
                <td>' . ($e->section->name ?? '-') . '</td>
                <td>' . ($e->enrollment_date ?? '-') . '</td>
            </tr>';
        }

        return response($html);
    }

    return view('Admin.enrollment', compact(
        'enrollments',
        'students',
        'programs',
        'sessions',
        'sections'
    ));
}


    // ✅ AJAX
    public function getOfferedCourses(Request $request)
    {
        $courses = OfferedCourse::with('course')
            ->where('program_id', (int)$request->program_id)
            ->where('semester', $request->semester)
            ->get();

        return response()->json($courses);
    }

    // ✅ STORE
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,id',
            'program_id' => 'required|exists:program,id',
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',

            'offered_course_id' => 'required|array|min:1',
            'offered_course_id.*' => 'exists:offered_courses,id',

            'semester' => 'required|integer|min:1|max:12',
            'enrollment_date' => 'required|date',

            'grade' => 'nullable|string|max:10'
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->offered_course_id as $courseId) {

                $exists = Enrollment::where([
                    'student_id' => $request->student_id,
                    'offered_course_id' => $courseId,
                    'semester' => $request->semester
                ])->exists();

                if ($exists) continue;

                Enrollment::create([
                    'student_id' => $request->student_id,
                    'program_id' => $request->program_id,
                    'session_id' => $request->session_id,
                    'section_id' => $request->section_id,
                    'offered_course_id' => $courseId,
                    'semester' => $request->semester,
                    'enrollment_date' => $request->enrollment_date,
                    'grade' => $request->grade ?? null
                ]);
            }

            DB::commit();

            return back()->with('success', 'Enrollment successful');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
