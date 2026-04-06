<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;


class AuthController extends Controller
{
    public function login(){
        return view('Auth.login');
    }


    public function register(){
        return view('Auth.register');
    }


public function webLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Find user
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // User not found
        return redirect()->route('login')->with('error', 'User not found.');
    }

    if (!Hash::check($request->password, $user->password)) {
        // Wrong password
        return redirect()->route('login')->with('error', 'Incorrect password. Please try again.');
    }

    // Login user
    Auth::login($user);

    // Regenerate session for security
    $request->session()->regenerate();

    // Check role from user_role_assignment table
    $roleAssignment = \DB::table('user_role_assignment')
                        ->where('user_id', $user->id)
                        ->first();

    if (!$roleAssignment) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'User role not assigned. Contact admin.');
    }

    // Get role name (assuming user_role_assignment has role_id)
    $role = \DB::table('role')
               ->where('id', $roleAssignment->role_id)
               ->value('name');

    if (!$role) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'User role not defined. Contact admin.');
    }

    $role = strtolower(trim($role));

    // Allowed roles
    $allowedRoles = [
        'finance officer',
        'finance student',
        'finance deputy controller',
        'finance clerk',
        'senior finance team',
        'auditor',
        'hostel warden'
    ];

    if (in_array($role, $allowedRoles)) {
        return redirect()->route('dashboard');
    } else {
        Auth::logout();
        return redirect()->route('login')->with('error', 'User role not allowed.');
    }
}

}