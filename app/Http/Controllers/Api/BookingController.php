<?php

namespace App\Http\Controllers\Api;

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
        try {
            $bookings = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.Room:roomID,roomnum',
                'cottageBookings.Cottage:cottageID,cottagename',
                'billing.payments',
                'menuBookings.menu:menuID,menuname,price' // ✅ include menus
            ])
            ->where('guestID', $guestID)
            ->get([
                'bookingID',
                'guestID',
                'guestamount',
                'childguest',
                'adultguest',
                'totalprice',
                'bookingcreated',
                'bookingstart',
                'bookingend',
                'status',
                'amenityID'
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

    // ✅ GET single booking by bookingID
    public function show($id)
    {
        try {
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'Amenity:amenityID,amenityname,description',
                'roomBookings.Room:roomID,roomname',
                'cottageBookings.Cottage:cottageID,cottagename',
                'billing.payments',
                'menuBookings.menu:menuID,menuname,price'
            ])
            ->findOrFail($id);

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
        DB::beginTransaction();
        try {
            $bookingData = [
                'guestamount'    => $request->input('guestamount'),
                'childguest'     => $request->input('childGuest'),
                'adultguest'     => $request->input('adultGuest'),
                'totalprice'     => $request->input('totalPrice'),
                'bookingcreated' => now(),
                'bookingend'     => $request->input('bookingEnd'),
                'bookingstart'   => $request->input('bookingStart'),
                'status'         => $request->input('status', 'pending'),
                'guestID'        => $request->input('guestID'),
                'amenityID'      => $request->input('amenityID')
            ];

            $booking = BookingTable::create($bookingData);

            // Rooms
            if ($request->has('roomBookings')) {
                foreach ($request->roomBookings as $room) {
                    RoomBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'roomID'    => $room['roomID'],
                        'price'     => $room['price'] ?? null,
                    ]);
                }
            }

            // Cottages
            if ($request->has('cottageBookings')) {
                foreach ($request->cottageBookings as $cottage) {
                    CottageBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'cottageID' => $cottage['cottageID'],
                    ]);
                }
            }

            // ✅ Menus
            if ($request->has('menuBookings')) {
                foreach ($request->menuBookings as $menu) {
                    MenuBookingTable::create([
                        'booking_id' => $booking->bookingID,
                        'menu_id'    => $menu['menuID'],
                        'quantity'   => $menu['quantity'] ?? 1,
                        'price'      => $menu['price'] ?? 0,
                        'status'     => $menu['status'] ?? 'pending',
                    ]);
                }
            }

            // Billing + Payments
            if ($request->has('billing') && !empty($request->billing)) {
                $billing = BillingTable::create([
                    'totalamount' => $request->billing['totalamount'] ?? 0,
                    'datebilled'  => $request->billing['datebilled'] ?? now(),
                    'status'      => $request->billing['status'] ?? 'unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                if (!empty($request->billing['payments'])) {
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
                }
            }

            DB::commit();
            return response()->json($booking, 201);
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $booking = BookingTable::findOrFail($id);
            $booking->update($request->only([
                'guestamount',
                'childguest',
                'adultguest',
                'totalprice',
                'bookingend',
                'bookingstart',
                'status',
                'guestID',
                'amenityID'
            ]));

            // Refresh related data
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete(); // ✅ clear old menus

            if ($request->has('roomBookings')) {
                foreach ($request->roomBookings as $room) {
                    RoomBookTable::create([
                        'bookingID' => $id,
                        'roomID'    => $room['roomID'],
                        'price'     => $room['price'] ?? null,
                    ]);
                }
            }

            if ($request->has('cottageBookings')) {
                foreach ($request->cottageBookings as $cottage) {
                    CottageBookTable::create([
                        'bookingID' => $id,
                        'cottageID' => $cottage['cottageID'],
                    ]);
                }
            }

            // ✅ Update menus
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
            }

            // Update or create billing
            if ($request->has('billing')) {
                $billing = BillingTable::updateOrCreate(
                    ['bookingID' => $id],
                    [
                        'totalamount' => $request->billing['totalamount'],
                        'datebilled'  => $request->billing['datebilled'],
                        'status'      => $request->billing['status'],
                        'guestID'     => $booking->guestID,
                    ]
                );

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
                }
            }

            DB::commit();
            return response()->json($booking);
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
