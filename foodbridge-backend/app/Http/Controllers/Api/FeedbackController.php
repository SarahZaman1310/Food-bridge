<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display all feedback.
     */
    public function index()
    {
        $feedback = Feedback::with('delivery')->get();

        return response()->json([
            'success' => true,
            'message' => 'Feedback retrieved successfully',
            'data' => $feedback
        ]);
    }

    /**
     * Store new feedback.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'submitted_by_type' => 'required|string',
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feedback created successfully',
            'data' => $feedback
        ], 201);
    }

    /**
     * Display a specific feedback.
     */
    public function show(Feedback $feedback)
    {
        return response()->json([
            'success' => true,
            'message' => 'Feedback retrieved successfully',
            'data' => $feedback->load('delivery')
        ]);
    }

    /**
     * Update existing feedback.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'delivery_id' => 'sometimes|required|exists:deliveries,id',
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'submitted_by_type' => 'sometimes|required|string',
        ]);

        $feedback->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feedback updated successfully',
            'data' => $feedback
        ]);
    }

    /**
     * Delete feedback.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback deleted successfully'
        ]);
    }
}