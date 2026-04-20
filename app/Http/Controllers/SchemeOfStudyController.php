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
            'title' => 'required|string|max:255|unique:scheme_of_study,title',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $scheme = SchemeOfStudy::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scheme created successfully',
            'data' => $scheme
        ]);
    }

    public function edit($id)
    {
        $scheme = SchemeOfStudy::find($id);

        if (!$scheme) {
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $scheme
        ]);
    }

    public function update(Request $request, $id)
    {
        $scheme = SchemeOfStudy::find($id);

        if (!$scheme) {
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }

        $scheme->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $scheme = SchemeOfStudy::find($id);

        if (!$scheme) {
            return response()->json([
                'success' => false,
                'message' => 'Scheme not found'
            ]);
        }

        $scheme->delete();

        return response()->json([
            'success' => true,
            'message' => 'Scheme deleted successfully'
        ]);
    }

    public function getSchemes()
    {
        $schemes = SchemeOfStudy::all();
        return response()->json($schemes);
    }
}
