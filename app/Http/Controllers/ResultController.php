<?php

namespace App\Http\Controllers;

use App\Models\Student;

class ResultController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('Admin.results.index', compact('students'));
    }

    public function show($id)
    {
        $student = Student::with('enrollments.offeredCourse.course')
            ->findOrFail($id);

        // Group by semester
        $groupedEnrollments = $student->enrollments->groupBy('semester');

        return view('Admin.results.show', compact('student', 'groupedEnrollments'));
    }
}
