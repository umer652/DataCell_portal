<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchemeOfStudyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CoursePrerequisiteController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\ResultController;

// ==================== EXISTING ROUTES ====================
// (Keep all your existing routes here)

Route::get('/', function () {
    return view('Auth.login');
});

// Show login page
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Handle login POST
Route::post('/login', [AuthController::class, 'webLogin'])->name('webLogin');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::get('/sos', [SchemeOfStudyController::class, 'index'])->name('scheme_of_study');
Route::post('/sos-create', [SchemeOfStudyController::class, 'store'])->name('scheme.store');
Route::get('/schemes-list', [SchemeOfStudyController::class, 'getSchemes'])->name('scheme.list');

// student controller
Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
Route::post('/dashboard', [StudentController::class, 'store'])->name('students.store');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::post('/students/import-excel', [StudentController::class, 'importExcel'])->name('students.import.excel');
Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])->name('students.download.template');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');

//course Controller
Route::get('/courses', [CourseController::class, 'index'])->name('add-courses');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::post('/courses/import', [CourseController::class, 'import'])->name('courses.import');
Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');

// enrollment controller
Route::prefix('enrollments')->group(function () {
    Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/store', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::get('/{id}/edit', [EnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('/{id}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::delete('/{id}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

// Helper route for getting offered courses
Route::get('/get-offered-courses', [EnrollmentController::class, 'getOfferedCourses'])->name('enrollment.getOfferedCourses');

//Allocation Controller
Route::prefix('allocations')->group(function () {
    Route::get('/index', [AllocationController::class, 'index'])->name('allocation.index');
    Route::get('/data', [AllocationController::class, 'getData'])->name('allocations.data');
    Route::post('/store', [AllocationController::class, 'store'])->name('allocations.store');
    Route::get('/{id}/edit', [AllocationController::class, 'edit'])->name('allocations.edit');
    Route::put('/{id}', [AllocationController::class, 'update'])->name('allocations.update');
    Route::delete('/{id}', [AllocationController::class, 'destroy'])->name('allocations.destroy');
    Route::post('/bulk-delete', [AllocationController::class, 'bulkDelete'])->name('allocations.bulk-delete');
});

// Result Controller
Route::resource('results', ResultController::class);

// Helper routes
Route::get('/get-courses/{program_id}', [AllocationController::class, 'getCoursesByProgram']);
Route::get('/get-active-scheme/{program_id}', [AllocationController::class, 'getActiveSchemeByProgram']);
Route::get('/get-schemes/{program_id}', [AllocationController::class, 'getSchemesByProgram']);
Route::get('/get-teachers-by-course/{course_id}', [AllocationController::class, 'getTeachersByCourse']);

//Teacher Controller
Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');
Route::post('/teachers/store', [TeacherController::class, 'store'])->name('teachers.store');
Route::get('/teachers/edit/{id}', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::post('/teachers/update/{id}', [TeacherController::class, 'update'])->name('teachers.update');
Route::delete('/teachers/delete/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

// Course Prerequisite controller
Route::resource('course-prerequisite', CoursePrerequisiteController::class);
Route::get('/search-all', [CoursePrerequisiteController::class, 'searchAll'])->name('course-prerequisite.search');

// ==================== TRANSCRIPT ROUTES (ADD THESE) ====================

// Transcript routes - list all students
Route::get('/admin/transcripts', [TranscriptController::class, 'index'])->name('transcripts.index');

// View transcript for specific student (using student ID - primary key)
Route::get('/admin/transcript/{id}', [TranscriptController::class, 'show'])->name('transcript.show');

// Download PDF version
Route::get('/admin/transcript/{id}/pdf', [TranscriptController::class, 'downloadPDF'])->name('transcript.pdf');

// Print version (opens print dialog)
Route::get('/admin/transcript/{id}/print', [TranscriptController::class, 'print'])->name('transcript.print');

// ==================== END TRANSCRIPT ROUTES ====================
