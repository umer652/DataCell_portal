<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CoursesImport;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('Admin.add-course', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => [
                'required',
                'regex:/^[A-Za-z]{3}-[0-9]{3}$/',
                'unique:course,course_code' // ✅ FIXED
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
