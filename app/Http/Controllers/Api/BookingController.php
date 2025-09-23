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

    // ✅ GET single booking
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
                'guestamount'    => $request->input('guestamount') ?? $request->input('guestAmount'),
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

            // ✅ Rooms
            foreach ($normalized['roomBookings'] as $room) {
                RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $room['roomID'],
                    'price'       => $room['price'] ?? 0,
                    'bookingDate' => $room['bookingDate'] ?? null,
                ]);
            }

            // ✅ Cottages
            foreach ($normalized['cottageBookings'] as $cottage) {
                CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottage['cottageID'],
                    'price'       => $cottage['price'] ?? 0,
                    'bookingDate' => $cottage['bookingDate'] ?? null,
                ]);
            }

            // ✅ Menus
            foreach ($normalized['menuBookings'] as $menu) {
                MenuBookingTable::create([
                    'booking_id' => $booking->bookingID,
                    'menu_id'    => $menu['menuID'] ?? $menu['menu_id'],
                    'quantity'   => $menu['quantity'] ?? 1,
                    'price'      => $menu['price'] ?? 0,
                    'status'     => $menu['status'] ?? 'pending',
                ]);
            }

            // ✅ Billing + Payments
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


    // ✅ Normalize request (camelCase + snake_case)
    private function normalize(Request $request)
    {
        return [
            'guestamount'      => $request->input('guestamount') ?? $request->input('guestAmount') ?? 0,
            'childguest'       => $request->input('childguest') ?? $request->input('childGuest') ?? 0,
            'adultguest'       => $request->input('adultguest') ?? $request->input('adultGuest') ?? 0,
            'totalprice'       => $request->input('totalprice') ?? $request->input('totalPrice') ?? 0,
            'bookingstart'     => $request->input('bookingstart') ?? $request->input('bookingStart'),
            'bookingend'       => $request->input('bookingend') ?? $request->input('bookingEnd'),
            'status'           => $request->input('status'),
            'guestID'          => $request->input('guestID'),
            'amenityID'        => $request->input('amenityID') ?? ($request->input('amenity.amenityID') ?? null),

            // relations
            'roomBookings'     => $request->input('roomBookings') ?? $request->input('room_bookings') ?? [],
            'cottageBookings'  => $request->input('cottageBookings') ?? $request->input('cottage_bookings') ?? [],
            'menuBookings'     => $request->input('menuBookings') ?? $request->input('menu_bookings') ?? [],
            'billing'          => $request->input('billing') ?? null,
        ];
    }

    // ✅ Update booking
    public function update(Request $request, $id)
    {
        Log::info("➡️ update booking called", [
            'bookingID' => $id,
            'payload'   => $request->all()
        ]);

        DB::beginTransaction();
        try {
            $booking = BookingTable::findOrFail($id);

            // normalize request
            $data = $this->normalize($request);

            // ✅ Update booking main data
            $booking->update([
                'guestamount'  => $data['guestamount'],
                'childguest'   => $data['childguest'],
                'adultguest'   => $data['adultguest'],
                'totalprice'   => $data['totalprice'],
                'bookingstart' => $data['bookingstart'],
                'bookingend'   => $data['bookingend'],
                'status'       => $data['status'] ?? $booking->status,
                'guestID'      => $data['guestID'] ?? $booking->guestID,
                'amenityID'    => $data['amenityID'] ?? $booking->amenityID,
            ]);
            Log::info("📝 Booking updated", ['bookingID' => $id]);

            // ✅ Clear old related data
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete();

            // ✅ Recreate room bookings
            foreach ($data['roomBookings'] as $room) {
                RoomBookTable::create([
                    'bookingID'   => $id,
                    'roomID'      => $room['roomID'] ?? ($room['room']['roomID'] ?? null),
                    'price'       => $room['price'] ?? ($room['room']['price'] ?? 0),
                    'bookingDate' => $room['bookingDate'] ?? null,
                ]);
            }

            // ✅ Recreate cottage bookings
            foreach ($data['cottageBookings'] as $cottage) {
                CottageBookTable::create([
                    'bookingID'   => $id,
                    'cottageID'   => $cottage['cottageID'] ?? ($cottage['cottage']['cottageID'] ?? null),
                    'price'       => $cottage['price'] ?? ($cottage['cottage']['price'] ?? 0),
                    'bookingDate' => $cottage['bookingDate'] ?? null,
                ]);
            }

            // ✅ Recreate menu bookings
            foreach ($data['menuBookings'] as $menu) {
                MenuBookingTable::create([
                    'booking_id' => $id,
                    'menu_id'    => $menu['menuID'] ?? ($menu['menu']['menuID'] ?? null),
                    'quantity'   => $menu['quantity'] ?? ($menu['menu']['qty'] ?? 1),
                    'price'      => $menu['price'] ?? ($menu['menu']['price'] ?? 0),
                    'status'     => $menu['status'] ?? ($menu['menu']['status'] ?? 'pending'),
                ]);
            }

            // ✅ Update billing + payments
            if ($data['billing']) {
                $billing = BillingTable::updateOrCreate(
                    ['bookingID' => $id],
                    [
                        'totalamount' => $data['billing']['totalamount'] ?? 0,
                        'datebilled'  => $data['billing']['datebilled'] ?? now(),
                        'status'      => $data['billing']['status'] ?? 'unpaid',
                        'guestID'     => $booking->guestID,
                    ]
                );

                if (!empty($data['billing']['payments'])) {
                    $billing->payments()->delete();
                    foreach ($data['billing']['payments'] as $payment) {
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

            // ✅ Return refreshed booking with relations
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->find($id);

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
}
