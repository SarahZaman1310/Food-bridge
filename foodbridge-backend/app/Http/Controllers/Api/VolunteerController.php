<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VolunteerController extends Controller
{
    public function profile(Request $request)
    {
        $volunteer = $this->authenticatedVolunteer($request);

        return response()->json([
            'success' => true,
            'data' => $this->profileData($volunteer),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $volunteer = $this->authenticatedVolunteer($request);
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('volunteers', 'email')->ignore($volunteer->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('volunteers', 'phone')->ignore($volunteer->id),
            ],
            'availability_status' => ['required', Rule::in(['Available', 'Busy', 'Offline'])],
            'vehicle_type' => ['required', Rule::in(['None', 'Bicycle', 'Motorcycle', 'Car', 'Van'])],
        ]);

        DB::transaction(function () use ($user, $volunteer, $validated) {
            $user->update([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            $volunteer->update($validated);
        });

        return response()->json([
            'success' => true,
            'message' => 'Volunteer profile updated successfully',
            'data' => $this->profileData($volunteer->fresh()),
            'user' => $user->fresh(),
        ]);
    }

    public function assignedDeliveries(Request $request)
    {
        $volunteer = $this->authenticatedVolunteer($request);

        return response()->json([
            'success' => true,
            'data' => $volunteer->deliveries()
                ->with('foodRequest')
                ->latest()
                ->get(),
        ]);
    }

    // GET: /api/volunteers
    public function index()
    {
        $volunteers = Volunteer::orderBy('id')
            ->get()
            ->map(fn (Volunteer $volunteer) => $this->profileData($volunteer));

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

    private function authenticatedVolunteer(Request $request): Volunteer
    {
        abort_unless($request->user()->role === 'volunteer', 403, 'Volunteer access only.');

        return $request->user()->volunteer()->firstOrFail();
    }

    private function profileData(Volunteer $volunteer): array
    {
        $availabilityStatus = match (strtolower($volunteer->availability_status)) {
            'available' => 'Available',
            'busy' => 'Busy',
            default => 'Offline',
        };

        return [
            'id' => $volunteer->id,
            'full_name' => $volunteer->full_name,
            'email' => $volunteer->email,
            'phone' => $volunteer->phone,
            'availability_status' => $availabilityStatus,
            'vehicle_type' => $volunteer->vehicle_type ?: 'None',
        ];
    }
}
