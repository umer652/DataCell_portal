<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Program;
use App\Models\Session;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsExportTemplate;

class StudentController extends Controller
{
    // Helper method to handle AJAX responses
    private function renderView($view, $data = [])
    {
        // If it's an AJAX request, return only the content section
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view($view, $data)->render();
        }
        
        // For normal requests, return full layout
        return view($view, $data);
    }
    
    public function index(Request $request)
    {
        $query = Student::with(['user', 'program', 'session', 'section']);
        
        if ($request->session_filter) {
            $query->where('session_id', $request->session_filter);
        }
        
        $students = $query->latest('id')->get();
        $programs = Program::all();
        $sessions = Session::all();
        $sections = Section::all();
        
        $data = compact('students', 'programs', 'sessions', 'sections');
        
        // Use the helper method
        return $this->renderView('Admin.dashboard', $data);
    }
    
    // ADD THIS EDIT METHOD
    public function edit($id)
    {
        try {
            $student = Student::with('user')->findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $student->id,
                    'name' => $student->name,
                    'gender' => $student->gender,
                    'father_name' => $student->father_name,
                    'roll_no' => $student->roll_no,
                    'app_no' => $student->app_no,
                    'semester' => $student->semester,
                    'program_id' => $student->program_id,
                    'session_id' => $student->session_id,
                    'section_id' => $student->section_id,
                    'enrollment_date' => $student->enrollment_date,
                    'new_student' => $student->new_student,
                    'user' => [
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                        'department' => $student->user->department,
                        'designation' => $student->user->designation
                    ]
                ]);
            }
            
            return response()->json($student);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found: ' . $e->getMessage()
            ], 404);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/',
                ],
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'gender' => 'required',
                'father_name' => 'required',
                'roll_no' => [
                    'required',
                    'unique:student,roll_no',
                    'regex:/^([0-9]{3}-NUN-[0-9]{4}|[0-9]{3}-NUM-[0-9]{4}|NUN[0-9]{8})$/'
                ],
                'app_no' => [
                    'required',
                    'unique:student,app_no',
                    'regex:/^[a-zA-Z0-9]+$/'
                ],
                'semester' => 'required|integer',
                'program_id' => 'required|exists:program,id',
                'session_id' => 'required|exists:sessions,id',
                'section_id' => 'required|exists:sections,id',
                'department' => 'required',
            ]);
            
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $request->designation ?? 'student',
                'department' => $request->department,
            ]);
            
            $student = Student::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'gender' => $request->gender,
                'father_name' => $request->father_name,
                'roll_no' => $request->roll_no,
                'app_no' => $request->app_no,
                'semester' => $request->semester,
                'program_id' => $request->program_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'enrollment_date' => $request->enrollment_date,
                'new_student' => $request->new_student ?? 1,
            ]);
            
            DB::commit();
            
            // For AJAX requests, return JSON
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student created successfully!',
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->route('dashboard')->with('success', 'Student created successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $student = Student::with('user')->findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $student->user_id,
                'gender' => 'required',
                'father_name' => 'required',
                'roll_no' => 'required|unique:student,roll_no,' . $id,
                'app_no' => 'required|unique:student,app_no,' . $id,
                'semester' => 'required|integer',
                'program_id' => 'required|exists:program,id',
                'session_id' => 'required|exists:sessions,id',
                'section_id' => 'required|exists:sections,id',
                'department' => 'required',
            ]);
            
            DB::beginTransaction();
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation ?? 'student',
                'department' => $request->department,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            
            $student->user->update($userData);
            
            $student->update([
                'gender' => $request->gender,
                'father_name' => $request->father_name,
                'roll_no' => $request->roll_no,
                'app_no' => $request->app_no,
                'semester' => $request->semester,
                'program_id' => $request->program_id,
                'session_id' => $request->session_id,
                'section_id' => $request->section_id,
                'enrollment_date' => $request->enrollment_date,
                'new_student' => $request->new_student,
            ]);
            
            DB::commit();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student updated successfully!',
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->route('dashboard')->with('success', 'Student updated successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            
            return back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            
            return response()->json(['success' => true, 'message' => 'Student deleted successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function importExcel(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            ]);
            
            $import = new StudentsImport();
            Excel::import($import, $request->file('excel_file'));
            
            $successCount = $import->getSuccessCount();
            $failedCount = $import->getFailedCount();
            $failures = $import->failures();
            
            if ($failedCount > 0) {
                $errorDetails = [];
                foreach ($failures as $failure) {
                    $errorDetails[] = [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values()
                    ];
                }
                
                return response()->json([
                    'success' => false,
                    'message' => "Imported: {$successCount} students. Failed: {$failedCount}",
                    'errors' => $errorDetails
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$successCount} students"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function downloadTemplate()
    {
        try {
            return Excel::download(new StudentsExportTemplate(), 'students_import_template.xlsx');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Error downloading template: ' . $e->getMessage());
        }
    }
}