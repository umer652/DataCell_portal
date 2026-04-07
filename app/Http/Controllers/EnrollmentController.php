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
use Illuminate\Support\Facades\Validator;

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

        // SESSION FILTER
        if ($request->session_id) {
            $query->where('session_id', $request->session_id);
        }

        // SEMESTER FILTER
        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        // PROGRAM FILTER
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
                    <td class="action-buttons">
                        <button onclick="editEnrollment(' . $e->id . ')" class="edit-btn">Edit</button>
                        <button onclick="deleteEnrollment(' . $e->id . ')" class="delete-btn">Delete</button>
                    </td>
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

    // AJAX: Get offered courses
    public function getOfferedCourses(Request $request)
    {
        $courses = OfferedCourse::with('course')
            ->where('program_id', (int)$request->program_id)
            ->where('semester', $request->semester)
            ->get();

        return response()->json($courses);
    }

    // Get single enrollment for editing
    public function edit($id)
    {
        try {
            $enrollment = Enrollment::with(['student', 'offeredCourse.course', 'program', 'session', 'section'])
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $enrollment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment not found!'
            ], 404);
        }
    }

    // STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:student,id',
            'program_id' => 'required|exists:program,id',
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',
            'offered_course_id' => 'required|array|min:1',
            'offered_course_id.*' => 'exists:offered_courses,id',
            'semester' => 'required|integer|min:1|max:12',
            'enrollment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

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
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment successful!'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to enroll: ' . $e->getMessage()
            ], 500);
        }
    }

    // UPDATE
    // UPDATE - For single course update
// UPDATE - Allow multiple courses
public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'student_id' => 'required|exists:student,id',
        'program_id' => 'required|exists:program,id',
        'session_id' => 'required|exists:sessions,id',
        'section_id' => 'required|exists:sections,id',
        'offered_course_id' => 'required|array|min:1', // Keep as array
        'offered_course_id.*' => 'exists:offered_courses,id',
        'semester' => 'required|integer|min:1|max:12',
        'enrollment_date' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();
        
        // First, delete all existing enrollments for this student/semester
        Enrollment::where([
            'student_id' => $request->student_id,
            'semester' => $request->semester,
        ])->delete();
        
        // Then create new enrollments for selected courses
        foreach ($request->offered_course_id as $courseId) {
            Enrollment::create([
                'student_id' => $request->student_id,
                'program_id' => $request->program_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'offered_course_id' => $courseId,
                'semester' => $request->semester,
                'enrollment_date' => $request->enrollment_date,
            ]);
        }
        
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Enrollment updated successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to update: ' . $e->getMessage()
        ], 500);
    }
}
    // DELETE
    public function destroy($id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }
}