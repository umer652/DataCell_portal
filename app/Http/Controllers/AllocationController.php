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
     * STEP 3 (IMPORTANT): Get schemes for program (optional dropdown)
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
}
