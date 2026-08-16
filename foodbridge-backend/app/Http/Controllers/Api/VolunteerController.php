<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    // GET: /api/volunteers
    public function index()
    {
        $volunteers = Volunteer::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Volunteers retrieved successfully',
            'data' => $volunteers
        ]);
    }

    // POST: /api/volunteers
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:volunteers,email',
            'phone' => 'required|string|max:30|unique:volunteers,phone',
            'vehicle_type' => 'nullable|string|max:100',
            'availability_status' => 'sometimes|string|max:50',
        ]);

        $volunteer = Volunteer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Volunteer created successfully',
            'data' => $volunteer
        ], 201);
    }

    // GET: /api/volunteers/{id}
    public function show(string $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $volunteer
        ]);
    }

    // PUT/PATCH: /api/volunteers/{id}
    public function update(Request $request, string $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:volunteers,email,' . $volunteer->id,
            'phone' => 'sometimes|required|string|max:30|unique:volunteers,phone,' . $volunteer->id,
            'vehicle_type' => 'nullable|string|max:100',
            'availability_status' => 'sometimes|string|max:50',
        ]);

        $volunteer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Volunteer updated successfully',
            'data' => $volunteer
        ]);
    }

    // DELETE: /api/volunteers/{id}
    public function destroy(string $id)
    {
        $volunteer = Volunteer::findOrFail($id);

        $volunteer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Volunteer deleted successfully'
        ]);
    }
}