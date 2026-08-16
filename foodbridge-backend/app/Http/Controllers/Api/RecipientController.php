<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    /**
     * Display all recipients.
     */
    public function index()
    {
        $recipients = Recipient::with('ngo')->get();

        return response()->json([
            'success' => true,
            'message' => 'Recipients retrieved successfully',
            'data' => $recipients
        ]);
    }

    /**
     * Create a new recipient.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ngo_id' => 'required|exists:ngos,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'household_size' => 'nullable|integer|min:1',
        ]);

        $recipient = Recipient::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Recipient created successfully',
            'data' => $recipient->load('ngo')
        ], 201);
    }

    /**
     * Display one recipient.
     */
    public function show(Recipient $recipient)
    {
        return response()->json([
            'success' => true,
            'message' => 'Recipient retrieved successfully',
            'data' => $recipient->load('ngo')
        ]);
    }

    /**
     * Update a recipient.
     */
    public function update(Request $request, Recipient $recipient)
    {
        $validated = $request->validate([
            'ngo_id' => 'sometimes|required|exists:ngos,id',
            'full_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'household_size' => 'nullable|integer|min:1',
        ]);

        $recipient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Recipient updated successfully',
            'data' => $recipient->load('ngo')
        ]);
    }

    /**
     * Delete a recipient.
     */
    public function destroy(Recipient $recipient)
    {
        $recipient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recipient deleted successfully'
        ]);
    }
}