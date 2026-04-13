<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allocation;
use App\Models\Program;
use App\Models\Session;
use App\Models\Section;
use App\Models\Course;
use App\Models\SchemeOfStudy;
use App\Models\Teacher;
use App\Models\ProgramCourseScheme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllocationController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $schemes = SchemeOfStudy::all();
        $programs = Program::all();

        $sessions = Session::orderBy('start_date', 'desc')->get();
        $activeSession = Session::orderBy('start_date', 'desc')->first();

        $teachers = Teacher::all();
        $sections = Section::all();

        return view('Admin.allocation', compact(
            'courses',
            'schemes',
            'programs',
            'sessions',
            'teachers',
            'sections',
            'activeSession'
        ));
    }

    /**
     * Get all allocations as JSON for DataTable
     */
    public function getData(Request $request)
    {
        $allocations = Allocation::with(['program', 'scheme', 'course', 'teacher', 'session', 'section'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'data' => $allocations->map(function ($allocation, $index) {
                return [
                    'id' => $allocation->id,
                    'serial' => $index + 1,
                    'program' => $allocation->program->name ?? '-',
                    'scheme' => $allocation->scheme->title ?? '-',
                    'course' => $allocation->course->course_title ?? '-',
                    'course_code' => $allocation->course->course_code ?? '-',
                    'teacher' => $allocation->teacher->user->name ?? $allocation->teacher->name ?? '-',
                    'session' => $allocation->session->name ?? '-',
                    'section' => $allocation->section->name ?? '-',
                    'semester' => $allocation->semester,
                ];
            })
        ]);
    }

    /**
     * Store a new allocation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:program,id',  // Changed from programs to program
            'scheme_id' => 'required|exists:scheme_of_study,id',
            'course_id' => 'required|exists:course,id',    // Changed from courses to course
            'teacher_id' => 'required|exists:teacher,id',  // Changed from teachers to teacher
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',
            'semester' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check for duplicate allocation
        $exists = Allocation::where([
            'program_id' => $request->program_id,
            'scheme_id' => $request->scheme_id,
            'course_id' => $request->course_id,
            'session_id' => $request->session_id,
            'section_id' => $request->section_id,
            'semester' => $request->semester,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This teacher allocation already exists for the selected combination!'
            ], 409);
        }

        // Check if teacher is already allocated for same course/section/semester
        $teacherExists = Allocation::where([
            'teacher_id' => $request->teacher_id,
            'course_id' => $request->course_id,
            'session_id' => $request->session_id,
            'section_id' => $request->section_id,
            'semester' => $request->semester,
        ])->exists();

        if ($teacherExists) {
            return response()->json([
                'success' => false,
                'message' => 'This teacher is already allocated to the same course, section, and semester!'
            ], 409);
        }

        try {
            $allocation = Allocation::create([
                'program_id' => $request->program_id,
                'scheme_id' => $request->scheme_id,
                'course_id' => $request->course_id,
                'teacher_id' => $request->teacher_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'semester' => $request->semester,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Teacher allocated successfully!',
                'data' => $allocation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to allocate teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get allocation details for editing
     */
    public function edit($id)
    {
        try {
            $allocation = Allocation::with(['program', 'scheme', 'course', 'teacher', 'session', 'section'])
                ->find($id);
            
            if (!$allocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Allocation not found!'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $allocation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Allocation not found!'
            ], 404);
        }
    }

    /**
     * Update an existing allocation
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:program,id',  // Changed from programs to program
            'scheme_id' => 'required|exists:scheme_of_study,id',
            'course_id' => 'required|exists:course,id',    // Changed from courses to course
            'teacher_id' => 'required|exists:teacher,id',  // Changed from teachers to teacher
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',
            'semester' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $allocation = Allocation::findOrFail($id);

            // Check for duplicate allocation (excluding current record)
            $exists = Allocation::where([
                'program_id' => $request->program_id,
                'scheme_id' => $request->scheme_id,
                'course_id' => $request->course_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'semester' => $request->semester,
            ])->where('id', '!=', $id)
            ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another allocation with same details already exists!'
                ], 409);
            }

            // Check teacher conflict
            $teacherExists = Allocation::where([
                'teacher_id' => $request->teacher_id,
                'course_id' => $request->course_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'semester' => $request->semester,
            ])->where('id', '!=', $id)
            ->exists();

            if ($teacherExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'This teacher is already allocated to the same course, section, and semester!'
                ], 409);
            }

            $allocation->update([
                'program_id' => $request->program_id,
                'scheme_id' => $request->scheme_id,
                'course_id' => $request->course_id,
                'teacher_id' => $request->teacher_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'semester' => $request->semester,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Allocation updated successfully!',
                'data' => $allocation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update allocation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an allocation
     */
    /**
 * Delete an allocation
 */
public function destroy($id)
{
    try {
        $allocation = Allocation::findOrFail($id);
        $allocation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Allocation deleted successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete allocation: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Bulk delete allocations
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:allocation,id'  // Changed from allocations to allocation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Allocation::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' allocation(s) deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete allocations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * STEP 1: Get courses by program
     * (via ProgramCourseScheme pivot)
     */
    public function getCoursesByProgram($program_id)
    {
        $courseIds = ProgramCourseScheme::where('program_id', $program_id)
            ->pluck('course_id');

        $courses = Course::whereIn('id', $courseIds)->get();

        return response()->json($courses);
    }

    /**
     * STEP 2: Get ACTIVE scheme for program (JOIN based)
     */
    public function getActiveSchemeByProgram($program_id)
    {
        $scheme = DB::table('program_scheme')
            ->join('scheme_of_study', 'program_scheme.scheme_id', '=', 'scheme_of_study.id')
            ->where('program_scheme.program_id', $program_id)
            ->where('scheme_of_study.is_active', 1)
            ->select(
                'scheme_of_study.id',
                'scheme_of_study.title'
            )
            ->first();

        return response()->json($scheme);
    }

    /**
     * STEP 3: Get schemes for program (optional dropdown)
     */
    public function getSchemesByProgram($program_id)
    {
        $schemes = DB::table('program_scheme')
            ->join('scheme_of_study', 'program_scheme.scheme_id', '=', 'scheme_of_study.id')
            ->where('program_scheme.program_id', $program_id)
            ->select(
                'scheme_of_study.id',
                'scheme_of_study.title',
                'scheme_of_study.is_active'
            )
            ->get();

        return response()->json($schemes);
    }

    /**
     * Get teachers by course (optional - for filtering)
     */
    public function getTeachersByCourse($course_id)
    {
        $teachers = Teacher::whereHas('allocations', function($q) use ($course_id) {
            $q->where('course_id', $course_id);
        })->get();

        return response()->json($teachers);
    }
}