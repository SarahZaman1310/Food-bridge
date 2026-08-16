<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    // GET: /api/deliveries
    public function index()
    {
        $deliveries = Delivery::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Deliveries retrieved successfully',
            'data' => $deliveries
        ]);
    }

    // POST: /api/deliveries
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:food_requests,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'pickup_time' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'delivery_status' => 'sometimes|string|max:50',
        ]);

        $delivery = Delivery::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery created successfully',
            'data' => $delivery
        ], 201);
    }

    // GET: /api/deliveries/{id}
    public function show(string $id)
    {
        $delivery = Delivery::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $delivery
        ]);
    }

    // PUT/PATCH: /api/deliveries/{id}
    public function update(Request $request, string $id)
    {
        $delivery = Delivery::findOrFail($id);

        $validated = $request->validate([
            'request_id' => 'sometimes|required|exists:food_requests,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'pickup_time' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'delivery_status' => 'sometimes|string|max:50',
        ]);

        $delivery->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery updated successfully',
            'data' => $delivery
        ]);
    }

    // DELETE: /api/deliveries/{id}
    public function destroy(string $id)
    {
        $delivery = Delivery::findOrFail($id);

        $delivery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delivery deleted successfully'
        ]);
    }
}