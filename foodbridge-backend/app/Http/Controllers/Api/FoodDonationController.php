<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FoodDonation;
use Illuminate\Http\Request;

class FoodDonationController extends Controller
{
    // GET: /api/food-donations
    // Only show donations belonging to the logged-in donor
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'donor') {
            return response()->json([
                'success' => false,
                'message' => 'Only donors can view donations.',
            ], 403);
        }

        $donor = $user->donor;

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor profile not found.',
            ], 404);
        }

        $donations = FoodDonation::with('donor')
            ->where('donor_id', $donor->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Your food donations retrieved successfully',
            'data' => $donations,
        ]);
    }


    // POST: /api/food-donations
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'donor') {
            return response()->json([
                'success' => false,
                'message' => 'Only donors can create food donations.',
            ], 403);
        }

        $donor = $user->donor;

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor profile not found.',
            ], 404);
        }

        $validated = $request->validate([
            'food_name' => 'required|string|max:255',
            'food_category' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'prepared_at' => 'nullable|date',
            'expiry_at' => 'required|date|after:prepared_at',
            'availability_status' => 'sometimes|string|max:100',
            'donation_status' => 'sometimes|string|max:100',
        ]);

        // Automatically assign the logged-in user's donor ID
        $validated['donor_id'] = $donor->id;

        $donation = FoodDonation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Food donation created successfully',
            'data' => $donation->load('donor'),
        ], 201);
    }


    // GET: /api/food-donations/{id}
    public function show(Request $request, string $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'donor') {
            return response()->json([
                'success' => false,
                'message' => 'Only donors can view donations.',
            ], 403);
        }

        $donor = $user->donor;

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor profile not found.',
            ], 404);
        }

        $donation = FoodDonation::with([
            'donor',
            'foodRequests',
        ])
            ->where('donor_id', $donor->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $donation,
        ]);
    }


    // PUT/PATCH: /api/food-donations/{id}
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'donor') {
            return response()->json([
                'success' => false,
                'message' => 'Only donors can update donations.',
            ], 403);
        }

        $donor = $user->donor;

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor profile not found.',
            ], 404);
        }

        $donation = FoodDonation::where('donor_id', $donor->id)
            ->findOrFail($id);

        $validated = $request->validate([
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
            'data' => $donation->load('donor'),
        ]);
    }


    // DELETE: /api/food-donations/{id}
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'donor') {
            return response()->json([
                'success' => false,
                'message' => 'Only donors can delete donations.',
            ], 403);
        }

        $donor = $user->donor;

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor profile not found.',
            ], 404);
        }

        $donation = FoodDonation::where('donor_id', $donor->id)
            ->findOrFail($id);

        $donation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Food donation deleted successfully',
        ]);
    }
}