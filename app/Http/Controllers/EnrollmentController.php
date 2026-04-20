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

        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->session_id) {
            $query->where('session_id', $request->session_id);
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        if ($request->program_id) {
            $query->where('program_id', $request->program_id);
        }

        $enrollments = $query->get();

        if ($request->ajax()) {
            $html = '';
            foreach ($enrollments as $e) {
                $html .= '
            <tr>
                <td>' . htmlspecialchars($e->student->name ?? '-') . '</td>
                <td>' . htmlspecialchars($e->offeredCourse->course->course_title ?? '-') . '</td>
                <td>' . htmlspecialchars($e->program->name ?? '-') . '</td>
                <td>' . htmlspecialchars($e->session->name ?? '-') . '</td>
                <td>' . htmlspecialchars($e->semester ?? '-') . '</td>
                <td>' . htmlspecialchars($e->section->name ?? '-') . '</td>
                <td>' . htmlspecialchars($e->enrollment_date ?? '-') . '</td>
                
                
                <td class="action-buttons">
                      <button onclick="window.editEnrollment(' . $e->id . ')" class="edit-btn">Edit</button>
                      <button onclick="window.deleteEnrollment(' . $e->id . ', this)" class="delete-btn">Delete</button>
                      <button onclick="window.viewResult(' . $e->id . ')" class="result-btn">Result</button>
                </td>
            </tr>';
            }

            if ($enrollments->isEmpty()) {
                return response('<tr><td colspan="8" style="text-align:center;">No enrollments found</td></tr>');
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

    public function getOfferedCourses(Request $request)
    {
        $courses = OfferedCourse::with('course')
            ->where('program_id', (int)$request->program_id)
            ->where('semester', $request->semester)
            ->get();

        return response()->json($courses);
    }

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
                    'offered_courses_id' => $courseId,
                    'semester' => $request->semester
                ])->exists();

                if ($exists) continue;

                Enrollment::create([
                    'student_id' => $request->student_id,
                    'program_id' => $request->program_id,
                    'session_id' => $request->session_id,
                    'section_id' => $request->section_id,
                    'offered_courses_id' => $courseId,
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

    public function update(Request $request, $id)
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

        try {

            DB::beginTransaction();

            Enrollment::where([
                'student_id' => $request->student_id,
                'semester' => $request->semester,
            ])->delete();

            foreach ($request->offered_course_id as $courseId) {
                Enrollment::create([
                    'student_id' => $request->student_id,
                    'program_id' => $request->program_id,
                    'session_id' => $request->session_id,
                    'section_id' => $request->section_id,
                    'offered_courses_id' => $courseId,
                    'semester' => $request->semester,
                    'enrollment_date' => $request->enrollment_date,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment updated successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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

    public function show($id)
    {
        $enrollment = Enrollment::with([
            'student',
            'offeredCourse.course'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'student' => $enrollment->student,
                'course' => $enrollment->offeredCourse->course->course_title ?? '-',
                'semester' => $enrollment->semester,
                'status' => $enrollment->status ?? 'N/A',
            ]
        ]);
    }

    public function promoteStudents()
    {
        try {
            DB::beginTransaction();

            $students = Enrollment::all()->groupBy('student_id');

            foreach ($students as $records) {

                $total = $records->sum('total_marks');
                $obtained = $records->sum('obtained_marks');

                $percentage = $total > 0 ? ($obtained / $total) * 100 : 0;

                if ($percentage >= 50) {

                    foreach ($records as $r) {
                        $r->semester += 1;
                        $r->status = 'promoted';
                        $r->save();
                    }
                } else {

                    foreach ($records as $r) {
                        $r->status = 'failed';
                        $r->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Students promoted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function classResultSummary()
    {

        try {

            $results = Enrollment::with('student')
                ->get()
                ->groupBy('student_id');

            $formatted = [];

            foreach ($results as $studentId => $records) {

                $student = $records->first()->student;

                $totalMarks = $records->sum('total_marks') ?? 0;

                $obtainedMarks = $records->sum(function ($r) {
                    return ($r->homework_marks ?? 0)
                        + ($r->lab_marks ?? 0)
                        + ($r->midterm_marks ?? 0)
                        + ($r->final_marks ?? 0);
                });

                return response()->json([
                    'class_info' => [
                        'program' => $first->program->name ?? '',
                        'section' => $first->section->name ?? '',
                        'session' => $first->session->name ?? '',
                        'semester' => $first->semester ?? '',
                    ],
                    'students' => $formatted
                ]);
                $first = $results->first()->first();

                if ($totalMarks == 0) {
                    $percentage = 0;
                } else {
                    $percentage = ($obtainedMarks / $totalMarks) * 100;
                }

                $gpa = $this->calculateGPA($percentage);

                $formatted[] = [
                    'student' => $student,
                    'total_marks' => $totalMarks,
                    'obtained_marks' => $obtainedMarks,
                    'percentage' => round($percentage, 2),
                    'gpa' => $gpa,
                    'passed' => $percentage >= 50,
                    'pass_subjects' => $records->where('status', 'pass')->count(),
                    'fail_subjects' => $records->where('status', 'fail')->count(),
                ];
            }

            return response()->json([
                'students' => $formatted
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateGPA($percentage)
    {
        if ($percentage >= 80) return 4.0;
        if ($percentage >= 70) return 3.0;
        if ($percentage >= 60) return 2.0;
        if ($percentage >= 50) return 1.0;
        return 0.0;
    }
}
