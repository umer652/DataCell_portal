<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SchemeOfStudy;
use App\Models\Course;
use App\Models\Program;
use App\Models\CoursePrerequisite;


class CoursePrerequisiteController extends Controller
{

    public function index(Request $request)
    {
        $query = CoursePrerequisite::with([
            'sos',
            'program',
            'course',
            'prerequisiteCourse',
        ]);

        if ($request->search_id) {
            $query->where('id', $request->search_id);
        }

        $prerequisites = $query->get();

        $schemes = SchemeOfStudy::all();
        $programs = Program::all();
        $courses = Course::all();

        return view('Admin.course-prerequisite', compact(
            'schemes',
            'programs',
            'courses',
            'prerequisites',
        ));
    }

    public function searchAll(Request $request)
    {
        $search = $request->search;

        $data = CoursePrerequisite::with(['course', 'prerequisiteCourse', 'program', 'sos'])
            ->whereHas('course', function ($q) use ($search) {
                $q->where('course_title', 'LIKE', "%$search%");
            })
            ->orWhereHas('prerequisiteCourse', function ($q) use ($search) {
                $q->where('course_title', 'LIKE', "%$search%");
            })
            ->orWhereHas('program', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })
            ->orWhereHas('sos', function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%");
            })
            ->get();

        return response()->json($data);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'prerequisite_course_id' => 'required|different:course_id',
            'scheme_id' => 'required',
            'program_id' => 'required',
        ]);

        $data = CoursePrerequisite::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Prerequisite added successfully',
            'data' => $data
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required',
            'prerequisite_course_id' => 'required|different:course_id',
            'scheme_id' => 'required',
            'program_id' => 'required',
        ]);

        $item = CoursePrerequisite::findOrFail($id);
        $item->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        CoursePrerequisite::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Deleted successfully');
    }
}