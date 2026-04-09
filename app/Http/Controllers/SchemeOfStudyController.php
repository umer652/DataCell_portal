<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchemeOfStudy;

class SchemeOfStudyController extends Controller
{
    public function index()
    {
        $schemes = SchemeOfStudy::all();
        return view('Admin.add-sos', compact('schemes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\s]+$/',
                    'unique:scheme_of_study,title'
                ],
                'credit_hrs' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:140',
                ],
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ], [
                'title.regex' => 'Title must contain both letters and numbers.',
                'title.unique' => 'This title already exists. Please use a different title.',
                'credit_hrs.integer' => 'Credit hours must be a number.',
                'credit_hrs.max' => 'Credit hours cannot exceed 140.',
                'credit_hrs.min' => 'Credit hours must be at least 1.',
            ]);

            $scheme = SchemeOfStudy::create([
                'title' => $request->title,
                'credit_hrs' => $request->credit_hrs,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scheme created successfully!',
                'data' => $scheme
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating scheme: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $scheme = SchemeOfStudy::findOrFail($id);
            
            $request->validate([
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\s]+$/',
                    'unique:scheme_of_study,title,' . $id
                ],
                'credit_hrs' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:140',
                ],
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ], [
                'title.regex' => 'Title must contain both letters and numbers.',
                'title.unique' => 'This title already exists. Please use a different title.',
                'credit_hrs.integer' => 'Credit hours must be a number.',
                'credit_hrs.max' => 'Credit hours cannot exceed 140.',
                'credit_hrs.min' => 'Credit hours must be at least 1.',
            ]);

            $scheme->update([
                'title' => $request->title,
                'credit_hrs' => $request->credit_hrs,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scheme updated successfully!',
                'data' => $scheme
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating scheme: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $scheme = SchemeOfStudy::findOrFail($id);
            $scheme->delete();

            return response()->json([
                'success' => true,
                'message' => 'Scheme deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting scheme: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $scheme = SchemeOfStudy::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $scheme
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Scheme not found'
            ], 404);
        }
    }
}