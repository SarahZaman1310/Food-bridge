<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use Illuminate\Http\Request;

class NgoController extends Controller
{
    // GET: /api/ngos
    public function index()
    {
        $ngos = Ngo::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'NGOs retrieved successfully',
            'data' => $ngos
        ]);
    }

    // POST: /api/ngos
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ngo_name' => 'required|string|max:255|unique:ngos,ngo_name',
            'registration_no' => 'required|string|max:255|unique:ngos,registration_no',
            'email' => 'required|email|unique:ngos,email',
            'phone' => 'required|string|max:30',
            'address' => 'nullable|string',
            'is_verified' => 'sometimes|boolean',
        ]);

        $ngo = Ngo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'NGO created successfully',
            'data' => $ngo
        ], 201);
    }

    // GET: /api/ngos/{id}
    public function show(string $id)
    {
        $ngo = Ngo::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $ngo
        ]);
    }

    // PUT/PATCH: /api/ngos/{id}
    public function update(Request $request, string $id)
    {
        $ngo = Ngo::findOrFail($id);

        $validated = $request->validate([
            'ngo_name' => 'sometimes|required|string|max:255|unique:ngos,ngo_name,' . $ngo->id,
            'registration_no' => 'sometimes|required|string|max:255|unique:ngos,registration_no,' . $ngo->id,
            'email' => 'sometimes|required|email|unique:ngos,email,' . $ngo->id,
            'phone' => 'sometimes|required|string|max:30',
            'address' => 'nullable|string',
            'is_verified' => 'sometimes|boolean',
        ]);

        $ngo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'NGO updated successfully',
            'data' => $ngo
        ]);
    }

    // DELETE: /api/ngos/{id}
    public function destroy(string $id)
    {
        $ngo = Ngo::findOrFail($id);
        $ngo->delete();

        return response()->json([
            'success' => true,
            'message' => 'NGO deleted successfully'
        ]);
    }
}