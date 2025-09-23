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

    // ✅ Normalize request
   private function normalize(Request $request)
{
    return [
        'guestamount'      => $request->input('guestamount')
                                ?? $request->input('guestAmount')
                                ?? $request->input('payload.guestamount')
                                ?? 0,

        'childguest'       => $request->input('childguest')
                                ?? $request->input('childGuest')
                                ?? $request->input('payload.childguest')
                                ?? 0,

        'adultguest'       => $request->input('adultguest')
                                ?? $request->input('adultGuest')
                                ?? $request->input('payload.adultguest')
                                ?? 0,

        'totalprice'       => $request->input('totalprice')
                                ?? $request->input('totalPrice')
                                ?? $request->input('payload.totalprice')
                                ?? 0,

        'bookingstart'     => $request->input('bookingstart')
                                ?? $request->input('bookingStart')
                                ?? $request->input('payload.bookingstart'),

        'bookingend'       => $request->input('bookingend')
                                ?? $request->input('bookingEnd')
                                ?? $request->input('payload.bookingend'),

        'status'           => $request->input('status')
                                ?? $request->input('payload.status'),

        'guestID'          => $request->input('guestID')
                                ?? $request->input('payload.guestID'),

        'amenityID'        => $request->input('amenityID')
                                ?? $request->input('payload.amenity.amenityID')
                                ?? ($request->input('amenity.amenityID') ?? null),

        // ✅ related collections
        'roomBookings'     => $request->input('roomBookings')
                                ?? $request->input('room_bookings')
                                ?? $request->input('payload.room_bookings')
                                ?? [],

        'cottageBookings'  => $request->input('cottageBookings')
                                ?? $request->input('cottage_bookings')
                                ?? $request->input('payload.cottage_bookings')
                                ?? [],

        'menuBookings'     => $request->input('menuBookings')
                                ?? $request->input('menu_bookings')
                                ?? $request->input('payload.menu_bookings')
                                ?? [],

        'billing'          => $request->input('billing')
                                ?? $request->input('payload.billing')
                                ?? null,
    ];
}


    // ✅ POST create booking
    public function store(Request $request)
    {
        Log::info("➡️ store booking called", ['payload' => $request->all()]);
        DB::beginTransaction();

        try {
            $data = $this->normalize($request);

            $booking = BookingTable::create([
                'guestamount'    => $data['guestamount'],
                'childguest'     => $data['childguest'],
                'adultguest'     => $data['adultguest'],
                'totalprice'     => $data['totalprice'],
                'bookingcreated' => now(),
                'bookingstart'   => $data['bookingstart'],
                'bookingend'     => $data['bookingend'],
                'status'         => $data['status'] ?? 'Pending',
                'guestID'        => $data['guestID'],
                'amenityID'      => $data['amenityID']
            ]);

            // ✅ Rooms
            foreach ($data['roomBookings'] as $room) {
                RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $room['roomID'] ?? ($room['room']['roomID'] ?? null),
                    'price'       => $room['price'] ?? ($room['room']['price'] ?? 0),
                    'bookingDate' => $room['bookingDate'] ?? now(),
                ]);
            }

            // ✅ Cottages
            foreach ($data['cottageBookings'] as $cottage) {
                CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottage['cottageID'] ?? ($cottage['cottage']['cottageID'] ?? null),
                    'price'       => $cottage['price'] ?? ($cottage['cottage']['price'] ?? 0),
                    'bookingDate' => $cottage['bookingDate'] ?? now(),
                ]);
            }

            // ✅ Menus
            foreach ($data['menuBookings'] as $menu) {
                MenuBookingTable::create([
                    'booking_id' => $booking->bookingID,
                    'menu_id'    => $menu['menuID'] ?? ($menu['menu']['menuID'] ?? null),
                    'quantity'   => $menu['quantity'] ?? ($menu['menu']['qty'] ?? 1),
                    'price'      => $menu['price'] ?? ($menu['menu']['price'] ?? 0),
                    'status'     => $menu['status'] ?? ($menu['menu']['status'] ?? 'pending'),
                ]);
            }

            // ✅ Billing + Payments
            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $data['billing']['datebilled'] ?? now(),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                if (!empty($data['billing']['payments'])) {
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

            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.room',
                'cottageBookings.cottage',
                'menuBookings.menu',
                'billing.payments'
            ])->find($booking->bookingID);

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
                'amenityID'    => $data['amenityID'],
            ]);

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
                    'bookingDate' => $room['bookingDate'] ?? now(),
                ]);
            }

            // ✅ Recreate cottage bookings
            foreach ($data['cottageBookings'] as $cottage) {
                CottageBookTable::create([
                    'bookingID'   => $id,
                    'cottageID'   => $cottage['cottageID'] ?? ($cottage['cottage']['cottageID'] ?? null),
                    'price'       => $cottage['price'] ?? ($cottage['cottage']['price'] ?? 0),
                    'bookingDate' => $cottage['bookingDate'] ?? now(),
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
                        'status'      => $data['billing']['status'] ?? 'Unpaid',
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
