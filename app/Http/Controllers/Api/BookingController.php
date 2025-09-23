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

            Log::info("✅ getByGuest success", [
                'guestID' => $guestID,
                'count'   => $bookings->count()
            ]);
            return response()->json($bookings, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ getByGuest failed", [
                'guestID' => $guestID,
                'error'   => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        Log::info("➡️ show booking called", ['bookingID' => $id]);
        try {
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->findOrFail($id);

            Log::info("✅ show booking success", ['bookingID' => $id]);
            return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ show booking failed", [
                'bookingID' => $id,
                'error'     => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ POST create booking
    public function store(Request $request)
    {
        Log::info("➡️ store booking called", ['payload' => $request->all()]);
        DB::beginTransaction();

        try {
            $normalized = $this->normalizePayload($request);

            $bookingData = [
                'guestamount'    => $request->input('guestamount'),
                'childguest'     => $request->input('childguest') ?? $request->input('childGuest'),
                'adultguest'     => $request->input('adultguest') ?? $request->input('adultGuest'),
                'totalprice'     => $request->input('totalprice') ?? $request->input('totalPrice'),
                'bookingcreated' => now(),
                'bookingend'     => $request->input('bookingend') ?? $request->input('bookingEnd'),
                'bookingstart'   => $request->input('bookingstart') ?? $request->input('bookingStart'),
                'status'         => $request->input('status', 'pending'),
                'guestID'        => $request->input('guestID'),
                'amenityID'      => $normalized['amenityID']
            ];

            $booking = BookingTable::create($bookingData);
            Log::info("📝 Booking created", ['bookingID' => $booking->bookingID]);

            // Rooms
            foreach ($normalized['roomBookings'] as $room) {
                RoomBookTable::create([
                    'bookingID' => $booking->bookingID,
                    'roomID'    => $room['roomID'],
                    'price'     => $room['price'] ?? 0,
                ]);
            }

            // Cottages
            foreach ($normalized['cottageBookings'] as $cottage) {
                CottageBookTable::create([
                    'bookingID' => $booking->bookingID,
                    'cottageID' => $cottage['cottageID'],
                ]);
            }

            // Menus
            foreach ($normalized['menuBookings'] as $menu) {
                MenuBookingTable::create([
                    'booking_id' => $booking->bookingID,
                    'menu_id'    => $menu['menuID'] ?? $menu['menu_id'],
                    'quantity'   => $menu['quantity'] ?? 1,
                    'price'      => $menu['price'] ?? 0,
                    'status'     => $menu['status'] ?? 'pending',
                ]);
            }

            // Billing + payments
            if ($normalized['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $normalized['billing']['totalamount'] ?? 0,
                    'datebilled'  => $normalized['billing']['datebilled'] ?? now(),
                    'status'      => $normalized['billing']['status'] ?? 'unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                if (!empty($normalized['billing']['payments'])) {
                    foreach ($normalized['billing']['payments'] as $payment) {
                        PaymentTable::create([
                            'totaltender' => $payment['totaltender'] ?? 0,
                            'totalchange' => $payment['totalchange'] ?? 0,
                            'datepayment' => $payment['datepayment'] ?? now(),
                            'guestID'     => $booking->guestID,
                            'billingID'   => $billing->billingID,
                            'refNumber'   => $payment['refNumber'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->find($booking->bookingID);

            Log::info("🎉 Booking store success", ['bookingID' => $booking->bookingID]);
            return response()->json($booking, 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ store booking failed", [
                'error'   => $e->getMessage(),
                'request' => $request->all()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ PUT update booking
    // ✅ PUT update booking
    public function update(Request $request, $id)
    {
        Log::info("➡️ update booking called", [
            'bookingID' => $id,
            'payload'   => $request->all()
        ]);

        DB::beginTransaction();
        try {
            $booking = BookingTable::findOrFail($id);

            // ✅ Update booking main data
            $booking->update([
                'guestamount'    => $request->input('guestamount'),
                'childguest'     => $request->input('childGuest'),
                'adultguest'     => $request->input('adultGuest'),
                'totalprice'     => $request->input('totalPrice'),
                'bookingend'     => $request->input('bookingEnd'),
                'bookingstart'   => $request->input('bookingStart'),
                'status'         => $request->input('status', $booking->status),
                'guestID'        => $request->input('guestID'),
                'amenityID'      => $request->input('amenityID'),
            ]);
            Log::info("📝 Booking updated", ['bookingID' => $id]);

            // ✅ Clear old related data
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete();

            // ✅ Recreate room bookings
            if ($request->has('roomBookings')) {
                foreach ($request->roomBookings as $room) {
                    RoomBookTable::create([
                        'bookingID' => $id,
                        'roomID'    => $room['roomID'],
                        'price'     => $room['price'] ?? 0,
                    ]);
                }
                Log::info("✅ Room bookings updated", ['count' => count($request->roomBookings)]);
            }

            // ✅ Recreate cottage bookings
            if ($request->has('cottageBookings')) {
                foreach ($request->cottageBookings as $cottage) {
                    CottageBookTable::create([
                        'bookingID' => $id,
                        'cottageID' => $cottage['cottageID'],
                    ]);
                }
                Log::info("✅ Cottage bookings updated", ['count' => count($request->cottageBookings)]);
            }

            // ✅ Recreate menu bookings
            if ($request->has('menuBookings')) {
                foreach ($request->menuBookings as $menu) {
                    MenuBookingTable::create([
                        'booking_id' => $id,
                        'menu_id'    => $menu['menuID'],
                        'quantity'   => $menu['quantity'] ?? 1,
                        'price'      => $menu['price'] ?? 0,
                        'status'     => $menu['status'] ?? 'pending',
                    ]);
                }
                Log::info("✅ Menu bookings updated", ['count' => count($request->menuBookings)]);
            }

            // ✅ Update billing + payments
            if ($request->has('billing')) {
                $billing = BillingTable::updateOrCreate(
                    ['bookingID' => $id],
                    [
                        'totalamount' => $request->billing['totalamount'] ?? 0,
                        'datebilled'  => $request->billing['datebilled'] ?? now(),
                        'status'      => $request->billing['status'] ?? 'unpaid',
                        'guestID'     => $booking->guestID,
                    ]
                );
                Log::info("✅ Billing updated", ['billingID' => $billing->billingID]);

                if (!empty($request->billing['payments'])) {
                    $billing->payments()->delete();
                    foreach ($request->billing['payments'] as $payment) {
                        PaymentTable::create([
                            'totaltender' => $payment['totaltender'] ?? 0,
                            'totalchange' => $payment['totalchange'] ?? 0,
                            'datepayment' => $payment['datepayment'] ?? now(),
                            'guestID'     => $booking->guestID,
                            'billingID'   => $billing->billingID,
                            'refNumber'   => $payment['refNumber'] ?? null,
                        ]);
                    }
                    Log::info("✅ Payments updated", ['count' => count($request->billing['payments'])]);
                }
            }

            DB::commit();

            // ✅ Return refreshed booking with relations
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->find($id);

            Log::info("🎉 Booking update success", ['bookingID' => $id]);
            return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ update booking failed", [
                'bookingID' => $id,
                'error'     => $e->getMessage(),
                'request'   => $request->all()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function normalizePayload(Request $request)
    {
        return [
            'roomBookings'     => $request->roomBookings ?? $request->room_bookings ?? [],
            'cottageBookings'  => $request->cottageBookings ?? $request->cottage_bookings ?? [],
            'menuBookings'     => $request->menuBookings ?? $request->menu_bookings ?? [],
            'amenityID'        => $request->amenityID ?? ($request->amenity['amenityID'] ?? null),
            'billing'          => $request->billing ?? null,
        ];
    }
}
