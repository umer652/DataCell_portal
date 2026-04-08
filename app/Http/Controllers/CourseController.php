<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CoursesImport;

class CourseController extends Controller
{
    // ==================== READ ====================
    public function index()
    {
        $courses = Course::all();
        return view('Admin.add-course', compact('courses'));
    }

    // ==================== CREATE ====================
    public function store(Request $request)
    {
        $request->validate([
            'course_code' => [
                'required',
                'regex:/^[A-Za-z]{3}-[0-9]{3}$/',
                'unique:course,course_code'
            ],
            'course_title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'course_code.regex' => 'Format must be ABC-123',
            'course_code.unique' => 'Record already exists!',
        ]);

        Course::create([
            'course_code'   => $request->course_code,
            'course_title'  => $request->course_title,
            'description'   => $request->description,
        ]);

        return back()->with('success', 'Course added successfully');
    }

    // ==================== UPDATE ====================
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'course_code' => [
                'required',
                'regex:/^[A-Za-z]{3}-[0-9]{3}$/',
                'unique:course,course_code,' . $id
            ],
            'course_title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'course_code.regex' => 'Format must be ABC-123',
            'course_code.unique' => 'Record already exists!',
        ]);

        $course->update([
            'course_code'   => $request->course_code,
            'course_title'  => $request->course_title,
            'description'   => $request->description,
        ]);

        return back()->with('success', 'Course updated successfully');
    }

    // ==================== DELETE ====================
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }

    // ==================== IMPORT ====================
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $import = new CoursesImport;

        Excel::import($import, $request->file('file'));

        if ($import->errorMessage) {
            return back()->with('error', $import->errorMessage);
        }

        return back()->with([
            'success' => 'Courses imported successfully',
            'inserted' => $import->inserted
        ]);
    }
}
