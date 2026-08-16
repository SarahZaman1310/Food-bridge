<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryUpdate;
use Illuminate\Http\Request;

class DeliveryUpdateController extends Controller
{
    public function index()
    {
        $updates = DeliveryUpdate::with('delivery')->get();

        return response()->json([
            'success' => true,
            'message' => 'Delivery updates retrieved successfully',
            'data' => $updates
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'update_status' => 'required|string|max:255',
            'update_time' => 'nullable|date',
            'location_note' => 'nullable|string',
            'updated_by' => 'nullable|string|max:255',
        ]);

        $update = DeliveryUpdate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery update created successfully',
            'data' => $update
        ], 201);
    }

    public function show(DeliveryUpdate $deliveryUpdate)
    {
        return response()->json([
            'success' => true,
            'message' => 'Delivery update retrieved successfully',
            'data' => $deliveryUpdate->load('delivery')
        ]);
    }

    public function update(Request $request, DeliveryUpdate $deliveryUpdate)
    {
        $validated = $request->validate([
            'delivery_id' => 'sometimes|required|exists:deliveries,id',
            'update_status' => 'sometimes|required|string|max:255',
            'update_time' => 'nullable|date',
            'location_note' => 'nullable|string',
            'updated_by' => 'nullable|string|max:255',
        ]);

        $deliveryUpdate->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery update updated successfully',
            'data' => $deliveryUpdate
        ]);
    }

    public function destroy(DeliveryUpdate $deliveryUpdate)
    {
        $deliveryUpdate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delivery update deleted successfully'
        ]);
    }
}