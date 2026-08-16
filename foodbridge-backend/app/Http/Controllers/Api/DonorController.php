<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    // GET: /api/donors
    public function index()
    {
        $donors = Donor::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Donors retrieved successfully',
            'data' => $donors
        ]);
    }

    // POST: /api/donors
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_type' => 'required|string|max:100',
            'email' => 'required|email|unique:donors,email',
            'phone' => 'required|string|max:30|unique:donors,phone',
            'address' => 'nullable|string',
        ]);

        $donor = Donor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Donor created successfully',
            'data' => $donor
        ], 201);
    }

    // GET: /api/donors/{id}
    public function show(string $id)
    {
        $donor = Donor::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $donor
        ]);
    }

    // PUT/PATCH: /api/donors/{id}
    public function update(Request $request, string $id)
    {
        $donor = Donor::findOrFail($id);

        $validated = $request->validate([
            'donor_name' => 'sometimes|required|string|max:255',
            'donor_type' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|unique:donors,email,' . $donor->id,
            'phone' => 'sometimes|required|string|max:30|unique:donors,phone,' . $donor->id,
            'address' => 'nullable|string',
        ]);

        $donor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Donor updated successfully',
            'data' => $donor
        ]);
    }

    // DELETE: /api/donors/{id}
    public function destroy(string $id)
    {
        $donor = Donor::findOrFail($id);

        $donor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Donor deleted successfully'
        ]);
    }
}