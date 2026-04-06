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


Route::get('/', function () {
    return view('Auth.login');
});

// Show login page
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Handle login POST
Route::post('/login', [AuthController::class, 'webLogin'])->name('webLogin');

Route::get('/register',[AuthController::class,'register'])->name('register');


// scheme of study controller
Route::get('/sos', [SchemeOfStudyController::class, 'index'])->name('scheme_of_study');
Route::post('/sos-create', [SchemeOfStudyController::class, 'store'])->name('scheme.store');

// student controller
Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
Route::post('/dashboard', [StudentController::class, 'store'])->name('students.store');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::post('/students/import-excel', [StudentController::class, 'importExcel'])->name('students.import.excel');
Route::get('/students/download-template', [StudentController::class, 'downloadTemplate'])->name('students.download.template');

//course Controller
Route::get('/courses',[CourseController::class,'index'])->name('add-courses');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::post('/courses/import', [CourseController::class, 'import'])->name('courses.import');


// enrollment controller
Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollment.index');
Route::post('/enrollment/store', [EnrollmentController::class, 'store'])->name('enrollment.store');
Route::get('/get-offered-courses', [EnrollmentController::class, 'getOfferedCourses'])
    ->name('enrollment.getOfferedCourses');


//Allocation Controller
Route::get('/allocation', [AllocationController::class, 'index'])
    ->name('allocation.index');


Route::get('/get-courses/{program_id}', 
    [AllocationController::class, 'getCoursesByProgram']
);

Route::get('/get-active-scheme/{program_id}', 
    [AllocationController::class, 'getActiveSchemeByProgram']
);

Route::get('/get-schemes/{program_id}', 
    [AllocationController::class, 'getSchemesByProgram']
);


//Teacher Controller
Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');

Route::post('/teachers/store', [TeacherController::class, 'store'])->name('teachers.store');

Route::get('/teachers/edit/{id}', [TeacherController::class, 'edit'])->name('teachers.edit');

Route::post('/teachers/update/{id}', [TeacherController::class, 'update'])->name('teachers.update');

Route::delete('/teachers/delete/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');


// Course Prerequsite controller
Route::resource('course-prerequisite', CoursePrerequisiteController::class);
Route::get('/search-all', [CoursePrerequisiteController::class, 'searchAll'])->name('course-prerequisite.search');