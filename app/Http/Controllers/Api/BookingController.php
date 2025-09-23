<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\BookingTable;
use App\Models\RoomBookTable;
use App\Models\CottageBookTable;
use App\Models\BillingTable;
use App\Models\PaymentTable;
use App\Models\MenuBookingTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // ✅ GET all bookings by guestID
    public function getByGuest($guestID)
    {
        Log::info("➡️ getByGuest called", ['guestID' => $guestID]);
        try {
            $bookings = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'billing.payments',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu'
            ])
                ->where('guestID', $guestID)
                ->get();

            return response()->json($bookings, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ getByGuest failed", [
                'guestID' => $guestID,
                'error'   => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ GET single booking
    public function show($id)
    {
        try {
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->findOrFail($id);

            return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ show booking failed", [
                'bookingID' => $id,
                'error'     => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ Normalize request (supports payload.* and direct keys)
     private function normalize(Request $request)
    {
        $source = $request->input('payload', $request->all());

        return [
            'guestamount'    => $source['guestamount'] ?? 0,
            'childguest'     => $source['childguest'] ?? 0,
            'adultguest'     => $source['adultguest'] ?? 0,
            'totalprice'     => $source['totalprice'] ?? 0,
            'bookingstart'   => $this->parseDate($source['bookingstart'] ?? null),
            'bookingend'     => $this->parseDate($source['bookingend'] ?? null),
            'status'         => $source['status'] ?? 'Pending',
            'guestID'        => $source['guestID'] ?? null,
            'amenityID'      => $source['amenityID'] ?? null,
            'roomIDs'        => $source['roomIDs'] ?? [],
            'cottageIDs'     => $source['cottageIDs'] ?? [],
            'menuIDs'        => $source['menuIDs'] ?? [],
            'menuQuantities' => $source['menuQuantities'] ?? [],
            'billing'        => $source['billing'] ?? null,
            'payments'       => $source['billing']['payments'] ?? []
        ];
    }

    private function parseDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d') : null;
    }

    // Create booking
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->normalize($request);

            $booking = BookingTable::create([
                'guestamount'  => $data['guestamount'],
                'childguest'   => $data['childguest'],
                'adultguest'   => $data['adultguest'],
                'totalprice'   => $data['totalprice'],
                'bookingstart' => $data['bookingstart'],
                'bookingend'   => $data['bookingend'],
                'status'       => $data['status'],
                'guestID'      => $data['guestID'],
                'amenityID'    => $data['amenityID'],
            ]);

            // Save related room bookings
            foreach ($data['roomIDs'] as $roomID) {
                RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            // Save cottage bookings
            foreach ($data['cottageIDs'] as $cottageID) {
                CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            // Save menu bookings
            foreach ($data['menuIDs'] as $index => $menuID) {
                MenuBookingTable::create([
                    'booking_id'  => $booking->bookingID,
                    'menu_id'     => $menuID,
                    'quantity'    => $data['menuQuantities'][$index] ?? 1,
                    'status'      => 'Pending',
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            // Billing + Payments
            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                foreach ($data['payments'] as $payment) {
                    PaymentTable::create([
                        'totaltender' => $payment['totaltender'] ?? 0,
                        'totalchange' => $payment['totalchange'] ?? 0,
                        'datepayment' => $this->parseDate($payment['datepayment'] ?? now()),
                        'guestID'     => $booking->guestID,
                        'billingID'   => $billing->billingID,
                        'refNumber'   => $payment['refNumber'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['bookingID' => $booking->bookingID], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ store booking failed", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update booking
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->normalize($request);

            $booking = BookingTable::findOrFail($id);
            $booking->update([
                'guestamount'  => $data['guestamount'],
                'childguest'   => $data['childguest'],
                'adultguest'   => $data['adultguest'],
                'totalprice'   => $data['totalprice'],
                'bookingstart' => $data['bookingstart'],
                'bookingend'   => $data['bookingend'],
                'status'       => $data['status'],
                'guestID'      => $data['guestID'],
                'amenityID'    => $data['amenityID'],
            ]);

            // Delete old related records
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete();
            PaymentTable::whereIn('billingID', function ($q) use ($id) {
                $q->select('billingID')->from('billing')->where('bookingID', $id);
            })->delete();
            BillingTable::where('bookingID', $id)->delete();

            // Recreate related records
            foreach ($data['roomIDs'] as $roomID) {
                RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            foreach ($data['cottageIDs'] as $cottageID) {
                CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            foreach ($data['menuIDs'] as $index => $menuID) {
                MenuBookingTable::create([
                    'booking_id'  => $booking->bookingID,
                    'menu_id'     => $menuID,
                    'quantity'    => $data['menuQuantities'][$index] ?? 1,
                    'status'      => 'Pending',
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
            }

            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                foreach ($data['payments'] as $payment) {
                    PaymentTable::create([
                        'totaltender' => $payment['totaltender'] ?? 0,
                        'totalchange' => $payment['totalchange'] ?? 0,
                        'datepayment' => $this->parseDate($payment['datepayment'] ?? now()),
                        'guestID'     => $booking->guestID,
                        'billingID'   => $billing->billingID,
                        'refNumber'   => $payment['refNumber'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['bookingID' => $booking->bookingID], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ update booking failed", ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
