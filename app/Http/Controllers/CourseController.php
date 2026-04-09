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

    // EDIT METHOD - ADD THIS
    public function edit($id)
    {
        try {
            $course = Course::findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $course->id,
                    'course_code' => $course->course_code,
                    'course_title' => $course->course_title,
                    'description' => $course->description
                ]);
            }
            
            return response()->json($course);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'course_code' => 'required|string|max:10|unique:course,course_code|regex:/^[A-Za-z]{3}-[0-9]{3}$/',
                'course_title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $course = Course::create([
                'course_code' => strtoupper($request->course_code),
                'course_title' => $request->course_title,
                'description' => $request->description
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Course created successfully!',
                    'data' => $course
                ]);
            }

            return redirect()->route('courses.index')->with('success', 'Course created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            throw $e;
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating course: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error creating course: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);
            
            $request->validate([
                'course_code' => 'required|string|max:10|unique:course,course_code,' . $id . '|regex:/^[A-Za-z]{3}-[0-9]{3}$/',
                'course_title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $course->update([
                'course_code' => strtoupper($request->course_code),
                'course_title' => $request->course_title,
                'description' => $request->description
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Course updated successfully!',
                    'data' => $course
                ]);
            }

            return redirect()->route('courses.index')->with('success', 'Course updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            throw $e;
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating course: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error updating course: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Course deleted successfully!'
                ]);
            }

            return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting course: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            Excel::import(new CoursesImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Courses imported successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing courses: ' . $e->getMessage()
            ], 500);
        }
    }
}