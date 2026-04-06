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
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'designation' => 'required',
        'department' => 'required',
    ]);

    DB::beginTransaction();

    try {

        // ✅ Get role dynamically from roles table
        $role = Role::where('name', 'Teacher')->firstOrFail();

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
            'role_id' => $role->id, // ✅ dynamic
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
            'message' => 'Teacher created successfully'
        ]);

    } catch (\Exception $e) {
        DB::rollback();

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    // EDIT
    public function edit($id)
    {
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
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user_id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'designation' => 'required',
            'department' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'designation' => $request->designation,
            'department' => $request->department,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Teacher updated successfully'
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user_id);

        UserRoleAssignment::where('user_id', $user->id)->delete();

        $teacher->delete();
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Teacher deleted successfully'
        ]);
    }
}
