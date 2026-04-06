<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Program;
use App\Models\Session;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;
    
    protected $successCount = 0;
    protected $failedCount = 0;
    
    public function model(array $row)
    {
        try {
            DB::beginTransaction();
            
            // Check if user already exists by email
            $user = User::where('email', $row['email'])->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password'] ?? 'password123'),
                    'designation' => $row['designation'] ?? 'student',
                    'department' => $row['department'] ?? '',
                ]);
            }
            
            // Check if student already exists by roll_no or app_no
            $existingStudent = Student::where('roll_no', $row['roll_no'])
                ->orWhere('app_no', $row['app_no'])
                ->first();
                
            if ($existingStudent) {
                throw new \Exception("Student with Roll No {$row['roll_no']} or App No {$row['app_no']} already exists");
            }
            
            // ONLY FETCH program - don't create
            $program = Program::where('name', $row['program'])->first();
            if (!$program) {
                throw new \Exception("Program '{$row['program']}' not found. Please add it first.");
            }
            
            // ONLY FETCH session - don't create
            $session = Session::where('name', $row['session'])->first();
            if (!$session) {
                throw new \Exception("Session '{$row['session']}' not found. Please add it first.");
            }
            
            // ONLY FETCH section - don't create
            $section = Section::where('name', $row['section'])->first();
            if (!$section) {
                throw new \Exception("Section '{$row['section']}' not found. Please add it first.");
            }
            
            // Create student - REMOVED the duplicate 'name' field
            $student = Student::create([
                'user_id' => $user->id,
                'name' => $row['name'],
                'gender' => $row['gender'],
                'father_name' => $row['father_name'],
                'roll_no' => $row['roll_no'],
                'app_no' => $row['app_no'],
                'semester' => $row['semester'],
                'program_id' => $program->id,
                'session_id' => $session->id,
                'section_id' => $section->id,
                'enrollment_date' => $row['enrollment_date'] ?? now(),
                'new_student' => $row['new_student'] ?? 1,
                'comment' => $row['comment'] ?? null,
            ]);
            
            DB::commit();
            $this->successCount++;
            
            return $student;
            
        } catch (Throwable $e) {
            DB::rollBack();
            $this->failedCount++;
            throw $e;
        }
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'gender' => 'required|in:Male,Female',
            'father_name' => 'required|string',
            'roll_no' => 'required|string',
            'app_no' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'program' => 'required|string',
            'session' => 'required|string',
            'section' => 'required|string',
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender must be Male or Female',
            'father_name.required' => 'Father name is required',
            'roll_no.required' => 'Roll number is required',
            'app_no.required' => 'Application number is required',
            'semester.required' => 'Semester is required',
            'semester.min' => 'Semester must be at least 1',
            'semester.max' => 'Semester must not exceed 8',
            'program.required' => 'Program is required',
            'session.required' => 'Session is required',
            'section.required' => 'Section is required',
        ];
    }
    
    public function getSuccessCount()
    {
        return $this->successCount;
    }
    
    public function getFailedCount()
    {
        return $this->failedCount;
    }
}