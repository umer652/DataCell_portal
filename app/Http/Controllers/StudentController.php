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
        
        return view('Admin.dashboard', compact('students', 'programs', 'sessions', 'sections'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => 'required',
            'father_name' => 'required',
            'roll_no' => 'required|unique:student,roll_no',
            'app_no' => 'required|unique:student,app_no',
            'semester' => 'required|integer',
            'program_id' => 'required|exists:programs,id',
            'session_id' => 'required|exists:sessions,id',
            'section_id' => 'required|exists:sections,id',
            'department' => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create User first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $request->designation ?? 'student',
                'department' => $request->department,
            ]);
            
            // Create Student linked to User
            $student = Student::create([
                'user_id' => $user->id,
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
            
            return redirect()->route('dashboard')->with('success', 'Student created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }
    
    public function update(Request $request, $id)
    {
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
        
        try {
            DB::beginTransaction();
            
            // Update User
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
            
            // Update Student
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
            
            return redirect()->route('dashboard')->with('success', 'Student updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $userId = $student->user_id;
            
            $student->delete();
            
            // Optional: Delete the associated user as well
            // User::find($userId)->delete();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // Excel Import Method
    // Excel Import Method - FIXED FOR AJAX
public function importExcel(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        
        \Log::info('Excel import started', [
            'file_name' => $request->file('excel_file')->getClientOriginalName()
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
            
            // Return JSON response for AJAX
            return response()->json([
                'success' => false,
                'message' => "Imported: {$successCount} students. Failed: {$failedCount}",
                'errors' => $errorDetails
            ]);
        }
        
        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$successCount} students"
        ]);
        
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        \Log::error('Excel validation error: ' . $e->getMessage());
        
        $failures = $e->failures();
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
            'message' => 'Validation errors in Excel file. Please check the file and try again.',
            'errors' => $errorDetails
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Excel import error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error importing file: ' . $e->getMessage()
        ], 500);
    }
}
    // Download Template
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