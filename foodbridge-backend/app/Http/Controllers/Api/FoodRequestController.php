<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodRequest;
use Illuminate\Http\Request;

class FoodRequestController extends Controller
{
    // GET: /api/food-requests
    public function index()
    {
        $foodRequests = FoodRequest::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Food requests retrieved successfully',
            'data' => $foodRequests
        ]);
    }

    // POST: /api/food-requests
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
            'donation_id' => 'required|exists:food_donations,id',
            'requested_qty' => 'required|numeric|min:0.01',
            'requested_at' => 'nullable|date',
            'request_status' => 'sometimes|string|max:50',
        ]);

        $foodRequest = FoodRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food request created successfully',
            'data' => $foodRequest
        ], 201);
    }

    // GET: /api/food-requests/{id}
    public function show(string $id)
    {
        $foodRequest = FoodRequest::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $foodRequest
        ]);
    }

    // PUT/PATCH: /api/food-requests/{id}
    public function update(Request $request, string $id)
    {
        $foodRequest = FoodRequest::findOrFail($id);

        $validated = $request->validate([
            'ngo_id' => 'sometimes|required|exists:ngos,id',
            'donation_id' => 'sometimes|required|exists:food_donations,id',
            'requested_qty' => 'sometimes|required|numeric|min:0.01',
            'requested_at' => 'sometimes|nullable|date',
            'request_status' => 'sometimes|string|max:50',
        ]);

        $foodRequest->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food request updated successfully',
            'data' => $foodRequest
        ]);
    }

    // DELETE: /api/food-requests/{id}
    public function destroy(string $id)
    {
        $foodRequest = FoodRequest::findOrFail($id);

        $foodRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Food request deleted successfully'
        ]);
    }
}