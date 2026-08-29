<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ==============================
        // 1. SUMMARY COUNTS
        // ==============================

        $totalDonors = DB::table('donors')->count();
        $totalNgos = DB::table('ngos')->count();
        $totalVolunteers = DB::table('volunteers')->count();
        $totalDonations = DB::table('food_donations')->count();
        $totalRequests = DB::table('food_requests')->count();
        $totalDeliveries = DB::table('deliveries')->count();
        $totalRecipients = DB::table('recipients')->count();


        // ==============================
        // 2. NGO VERIFICATION
        // ==============================

        $verifiedNgos = DB::table('ngos')
            ->where('is_verified', true)
            ->count();

        $pendingNgos = DB::table('ngos')
            ->where('is_verified', false)
            ->count();


        // ==============================
        // 3. NGO LIST
        // ==============================

        $ngos = DB::table('ngos')
            ->select(
                'id',
                'ngo_name',
                'registration_no',
                'email',
                'phone',
                'address',
                'is_verified',
                'created_at'
            )
            ->orderBy('is_verified')
            ->orderByDesc('created_at')
            ->get();


        // ==============================
        // 4. DONATIONS BY FOOD CATEGORY
        // ==============================

        $donationsByCategory = DB::table('food_donations')
            ->select(
                'food_category',
                DB::raw('COUNT(*) as total_donations'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('food_category')
            ->orderByDesc('total_donations')
            ->get();


        // ==============================
        // 5. DONATIONS BY DONOR
        // ==============================

        $donationsByDonor = DB::table('donors')
            ->leftJoin(
                'food_donations',
                'donors.id',
                '=',
                'food_donations.donor_id'
            )
            ->select(
                'donors.id',
                'donors.donor_name',
                DB::raw('COUNT(food_donations.id) as total_donations'),
                DB::raw(
                    'COALESCE(SUM(food_donations.quantity), 0) as total_quantity'
                )
            )
            ->groupBy(
                'donors.id',
                'donors.donor_name'
            )
            ->orderByDesc('total_donations')
            ->get();


        // ==============================
        // 6. REQUESTS BY NGO
        // ==============================

        $requestsByNgo = DB::table('ngos')
            ->leftJoin(
                'food_requests',
                'ngos.id',
                '=',
                'food_requests.ngo_id'
            )
            ->select(
                'ngos.id',
                'ngos.ngo_name',
                DB::raw('COUNT(food_requests.id) as total_requests')
            )
            ->groupBy(
                'ngos.id',
                'ngos.ngo_name'
            )
            ->orderByDesc('total_requests')
            ->get();


        // ==============================
        // 7. REQUESTS BY STATUS
        // ==============================

        $requestsByStatus = DB::table('food_requests')
            ->select(
                'request_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('request_status')
            ->get();


        // ==============================
        // 8. DELIVERIES BY STATUS
        // ==============================

        $deliveriesByStatus = DB::table('deliveries')
            ->select(
                'delivery_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('delivery_status')
            ->get();


        // ==============================
        // 9. VOLUNTEER WORKLOAD
        // ==============================

        $volunteerWorkload = DB::table('volunteers')
            ->leftJoin(
                'deliveries',
                'volunteers.id',
                '=',
                'deliveries.volunteer_id'
            )
            ->select(
                'volunteers.id',
                'volunteers.full_name',
                'volunteers.availability_status',
                DB::raw('COUNT(deliveries.id) as total_deliveries')
            )
            ->groupBy(
                'volunteers.id',
                'volunteers.full_name',
                'volunteers.availability_status'
            )
            ->orderByDesc('total_deliveries')
            ->get();


        // ==============================
        // 10. RECIPIENTS BY NGO
        // ==============================

        $recipientsByNgo = DB::table('ngos')
            ->leftJoin(
                'recipients',
                'ngos.id',
                '=',
                'recipients.ngo_id'
            )
            ->select(
                'ngos.id',
                'ngos.ngo_name',
                DB::raw('COUNT(recipients.id) as total_recipients'),
                DB::raw(
                    'COALESCE(SUM(recipients.household_size), 0) as total_household_size'
                )
            )
            ->groupBy(
                'ngos.id',
                'ngos.ngo_name'
            )
            ->orderByDesc('total_recipients')
            ->get();


        // ==============================
        // 11. DONATION REQUEST DETAILS
        // ==============================

        $requestDetails = DB::table('food_requests')
            ->join(
                'ngos',
                'food_requests.ngo_id',
                '=',
                'ngos.id'
            )
            ->join(
                'food_donations',
                'food_requests.donation_id',
                '=',
                'food_donations.id'
            )
            ->join(
                'donors',
                'food_donations.donor_id',
                '=',
                'donors.id'
            )
            ->select(
                'food_requests.id',
                'donors.donor_name',
                'food_donations.food_name',
                'food_donations.food_category',
                'ngos.ngo_name',
                'food_requests.requested_qty',
                'food_requests.request_status',
                'food_requests.requested_at'
            )
            ->orderByDesc('food_requests.requested_at')
            ->limit(10)
            ->get();


        // ==============================
        // 12. RECENT DELIVERIES
        // ==============================

        $recentDeliveries = DB::table('deliveries')
            ->join(
                'food_requests',
                'deliveries.request_id',
                '=',
                'food_requests.id'
            )
            ->join(
                'ngos',
                'food_requests.ngo_id',
                '=',
                'ngos.id'
            )
            ->join(
                'food_donations',
                'food_requests.donation_id',
                '=',
                'food_donations.id'
            )
            ->leftJoin(
                'volunteers',
                'deliveries.volunteer_id',
                '=',
                'volunteers.id'
            )
            ->select(
                'deliveries.id',
                'food_donations.food_name',
                'ngos.ngo_name',
                'volunteers.full_name as volunteer_name',
                'deliveries.pickup_time',
                'deliveries.delivered_at',
                'deliveries.delivery_status'
            )
            ->orderByDesc('deliveries.created_at')
            ->limit(10)
            ->get();


        // ==============================
        // RETURN DASHBOARD DATA
        // ==============================

        return response()->json([
            'success' => true,

            'summary' => [
                'donors' => $totalDonors,
                'ngos' => $totalNgos,
                'volunteers' => $totalVolunteers,
                'donations' => $totalDonations,
                'requests' => $totalRequests,
                'deliveries' => $totalDeliveries,
                'recipients' => $totalRecipients,
            ],

            'ngo_verification' => [
                'verified' => $verifiedNgos,
                'pending' => $pendingNgos,
            ],

            'ngos' => $ngos,

            'donations_by_category' => $donationsByCategory,

            'donations_by_donor' => $donationsByDonor,

            'requests_by_ngo' => $requestsByNgo,

            'requests_by_status' => $requestsByStatus,

            'deliveries_by_status' => $deliveriesByStatus,

            'volunteer_workload' => $volunteerWorkload,

            'recipients_by_ngo' => $recipientsByNgo,

            'request_details' => $requestDetails,

            'recent_deliveries' => $recentDeliveries,
        ]);
    }


    // ==============================
    // VERIFY NGO
    // PUT: /api/admin/ngos/{id}/verify
    // ==============================

    public function verifyNgo(string $id)
    {
        $ngo = Ngo::findOrFail($id);

        $ngo->update([
            'is_verified' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'NGO verified successfully.',
            'data' => $ngo,
        ]);
    }


    // ==============================
    // UNVERIFY NGO
    // PUT: /api/admin/ngos/{id}/unverify
    // ==============================

    public function unverifyNgo(string $id)
    {
        $ngo = Ngo::findOrFail($id);

        $ngo->update([
            'is_verified' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'NGO verification removed.',
            'data' => $ngo,
        ]);
    }
}