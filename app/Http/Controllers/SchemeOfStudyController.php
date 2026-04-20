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
                'regex:/^[0-9]+$/'
            ],
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ], [
            'title.regex' => 'Title must contain both letters and numbers.',
            'credit_hrs.regex' => 'Credit hours must contain only numbers.',
            'credit_hrs.max' => 'Credit hours cannot exceed 140.',
        ]);

        $scheme = SchemeOfStudy::create([
            'title' => $request->title,
            'credit_hrs' => $request->credit_hrs,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scheme created successfully',
            'data' => $scheme
        ]);
    }

    public function getSchemes()
    {
        $schemes = SchemeOfStudy::all();
        return response()->json($schemes);
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
                    'regex:/^[0-9]+$/'
                ],
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ], [
                'title.regex' => 'Title must contain both letters and numbers.',
                'credit_hrs.regex' => 'Credit hours must contain only numbers.',
                'credit_hrs.max' => 'Credit hours cannot exceed 140.',
            ]);

            $scheme->update([
                'title' => $request->title,
                'credit_hrs' => $request->credit_hrs,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Scheme updated successfully',
                'data' => $scheme
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update scheme: ' . $e->getMessage()
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
                'message' => 'Scheme deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete scheme: ' . $e->getMessage()
            ], 500);
        }
    }
}