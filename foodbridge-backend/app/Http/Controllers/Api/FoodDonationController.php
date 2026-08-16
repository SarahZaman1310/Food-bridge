<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodDonation;
use Illuminate\Http\Request;

class FoodDonationController extends Controller
{
    // GET: /api/food-donations
    public function index()
    {
        $donations = FoodDonation::with('donor')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Food donations retrieved successfully',
            'data' => $donations
        ]);
    }

    // POST: /api/food-donations
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'food_name' => 'required|string|max:255',
            'food_category' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'prepared_at' => 'nullable|date',
            'expiry_at' => 'required|date|after:prepared_at',
            'availability_status' => 'sometimes|string|max:100',
            'donation_status' => 'sometimes|string|max:100',
        ]);

        $donation = FoodDonation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food donation created successfully',
            'data' => $donation->load('donor')
        ], 201);
    }

    // GET: /api/food-donations/{id}
    public function show(string $id)
    {
        $donation = FoodDonation::with([
            'donor',
            'foodRequests'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $donation
        ]);
    }

    // PUT/PATCH: /api/food-donations/{id}
    public function update(Request $request, string $id)
    {
        $donation = FoodDonation::findOrFail($id);

        $validated = $request->validate([
            'donor_id' => 'sometimes|required|exists:donors,id',
            'food_name' => 'sometimes|required|string|max:255',
            'food_category' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|numeric|min:0.01',
            'unit' => 'sometimes|required|string|max:50',
            'prepared_at' => 'nullable|date',
            'expiry_at' => 'sometimes|required|date',
            'availability_status' => 'sometimes|string|max:100',
            'donation_status' => 'sometimes|string|max:100',
        ]);

        $donation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food donation updated successfully',
            'data' => $donation->load('donor')
        ]);
    }

    // DELETE: /api/food-donations/{id}
    public function destroy(string $id)
    {
        $donation = FoodDonation::findOrFail($id);

        $donation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Food donation deleted successfully'
        ]);
    }
}