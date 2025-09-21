<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingTable;
use App\Models\RoomBookTable;
use App\Models\CottageBookTable;
use App\Models\AmenityBookingTable;
use App\Models\BillingTable;
use App\Models\PaymentTable;
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
                'AmenityBook.amenity:amenityID,amenityname',
                'roomBookings.Room:roomID,roomnum',
                'cottageBookings.Cottage:cottageID,cottagename',
                'billing.payments', // 👈 include payments under billing
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
                    'status'
                ]);

            return response()->json($bookings, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to fetch bookings',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ GET booking by bookingID
    public function show($id)
    {
        try {
            $booking = BookingTable::with([
                'Guest:guestID,firstname,lastname,email',
                'AmenityBook.amenity:amenityID,amenityname',
                'roomBookings.Room:roomID,roomname',
                'cottageBookings.Cottage:cottageID,cottagename',
                'billing.payments', // 👈 include payments here too
            ])
                ->select([
                    'bookingID',
                    'guestID',
                    'guestamount',
                    'childguest',
                    'adultguest',
                    'totalprice',
                    'bookingcreated',
                    'bookingstart',
                    'bookingend',
                    'status'
                ])
                ->findOrFail($id);

            return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to fetch booking',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ POST create booking + billing + payments
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = BookingTable::create([
                'guestamount'    => $request->input('guestamount'),
                'childguest'     => $request->input('childGuest'),
                'adultguest'     => $request->input('adultGuest'),
                'totalprice'     => $request->input('totalPrice'),
                'bookingcreated' => now(),
                'bookingend'     => $request->input('bookingEnd'),
                'bookingstart'   => $request->input('bookingStart'),
                'status'         => $request->input('status', 'pending'),
                'guestID'        => $request->input('guestID'),
            ]);

            // Save related records (rooms, cottages, amenities)
            if ($request->has('roomBookings')) {
                foreach ($request->roomBookings as $room) {
                    RoomBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'roomID'    => $room['roomID'],
                        'price'     => $room['price'] ?? null,
                    ]);
                }
            }

            if ($request->has('cottageBookings')) {
                foreach ($request->cottageBookings as $cottage) {
                    CottageBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'cottageID' => $cottage['cottageID'],
                    ]);
                }
            }

            if ($request->has('amenityBook')) {
                foreach ($request->amenityBook as $amenity) {
                    AmenityBookingTable::create([
                        'booking_id' => $booking->bookingID,
                        'amenity_id' => $amenity['amenityID'],
                        'date'       => $amenity['date'] ?? now(),
                        'status'     => $amenity['status'] ?? 'pending',
                    ]);
                }
            }

            // ✅ Save billing + payments
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
                            'refNumber'   => $payment['refNumber'] ?? null,
                            'guestID'     => $booking->guestID,
                            'billingID'   => $billing->billingID,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json($booking, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ PUT update booking + billing + payments
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
                'bookingcreated',
                'bookingend',
                'bookingstart',
                'status',
                'guestID'
            ]));

            // refresh related tables
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            AmenityBookingTable::where('booking_id', $id)->delete();

            if ($request->has('rooms')) {
                foreach ($request->rooms as $roomID) {
                    RoomBookTable::create([
                        'bookingID' => $id,
                        'roomID'    => $roomID,
                    ]);
                }
            }

            if ($request->has('cottages')) {
                foreach ($request->cottages as $cottageID) {
                    CottageBookTable::create([
                        'bookingID' => $id,
                        'cottageID' => $cottageID,
                    ]);
                }
            }

            if ($request->has('amenities')) {
                foreach ($request->amenities as $amenity) {
                    AmenityBookingTable::create([
                        'booking_id' => $id,
                        'amenity_id' => $amenity['id'],
                        'date'       => $amenity['date'] ?? now(),
                        'status'     => $amenity['status'] ?? 'pending',
                    ]);
                }
            }

            // ✅ Update billing + payments
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

                // Refresh old payments
                PaymentTable::where('billingID', $billing->billingID)->delete();

                if (!empty($request->billing['payments'])) {
                    foreach ($request->billing['payments'] as $payment) {
                        PaymentTable::create([
                            'totaltender' => $payment['totaltender'] ?? 0,
                            'totalchange' => $payment['totalchange'] ?? 0,
                            'datepayment' => $payment['datepayment'] ?? now(),
                            'refNumber'   => $payment['refNumber'] ?? null,
                            'guestID'     => $booking->guestID,
                            'billingID'   => $billing->billingID,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
