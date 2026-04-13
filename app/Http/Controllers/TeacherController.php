<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Role;
use App\Models\UserRoleAssignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // LIST
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('Admin.teacher', compact('teachers'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Get role dynamically from roles table
            $role = Role::where('name', 'Teacher')->first();
            
            if (!$role) {
                // Create teacher role if it doesn't exist
                $role = Role::create(['name' => 'Teacher']);
            }

            // CREATE USER
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'designation' => $request->designation,
                'department' => $request->department,
            ]);

            // ASSIGN ROLE
            UserRoleAssignment::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'start_date' => now(),
                'end_date' => null,
            ]);

            // CREATE TEACHER
            Teacher::create([
                'user_id' => $user->id
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Teacher created successfully',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Error creating teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    // EDIT - Get teacher details for editing
    public function edit($id)
    {
        try {
            $teacher = Teacher::with('user')->findOrFail($id);

            return response()->json([
                'id' => $teacher->id,
                'user' => [
                    'name' => $teacher->user->name,
                    'email' => $teacher->user->email,
                    'designation' => $teacher->user->designation,
                    'department' => $teacher->user->department,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Teacher not found'
            ], 404);
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'designation' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'password' => 'nullable|min:6',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'designation' => $request->designation,
                'department' => $request->department,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Teacher updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);

            // Delete role assignment
            UserRoleAssignment::where('user_id', $user->id)->delete();

            // Delete teacher record
            $teacher->delete();
            
            // Delete user record
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting teacher: ' . $e->getMessage()
            ], 500);
        }
    }
}