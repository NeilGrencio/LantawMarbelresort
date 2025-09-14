<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingTable;
use App\Models\RoomBookTable;
use App\Models\CottageBookTable;
use App\Models\AmenityBookingTable;
use App\Models\BillingTable;
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
                'Guest',
                'AmenityBook.amenity',
                'roomBookings.Room',
                'cottageBookings.Cottage',
                'billing.payments',
                'billing.charge',
                'billing.guest'
            ])->where('guestID', $guestID)->get();


            Log::info("✅ getByGuest success", [
                'guestID' => $guestID,
                'count'   => $bookings->count(),
                'data'    => $bookings->toArray(),
            ]);

return response()->json($bookings, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ JSON encoding failed in getByGuest()", [
                'guestID' => $guestID,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'raw'     => isset($bookings) ? $bookings->toArray() : null,
            ]);

            return response()->json([
                'error'   => 'Failed to encode JSON',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ GET booking by bookingID
    public function show($id)
    {
        try {
            $booking = BookingTable::with([
                'Guest',
                'AmenityBook.amenity',
                'roomBookings.Room',
                'cottageBookings.Cottage',
                'billing.payments',
                'billing.charge',
                'billing.guest'
            ])->findOrFail($id);

            Log::info("✅ show success", [
                'bookingID' => $id,
                'data'      => $booking->toArray(),
            ]);

return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ JSON encoding failed in show()", [
                'bookingID' => $id,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
                'raw'       => isset($booking) ? $booking->toArray() : null,
            ]);

            return response()->json([
                'error'   => 'Failed to encode JSON',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ POST create booking + related tables
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = BookingTable::create($request->only([
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

            if ($request->has('rooms')) {
                foreach ($request->rooms as $roomID) {
                    RoomBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'roomID' => $roomID,
                    ]);
                }
            }

            if ($request->has('cottages')) {
                foreach ($request->cottages as $cottageID) {
                    CottageBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'cottageID' => $cottageID,
                    ]);
                }
            }

            if ($request->has('amenities')) {
                foreach ($request->amenities as $amenity) {
                    AmenityBookingTable::create([
                        'booking_id' => $booking->bookingID,
                        'amenity_id' => $amenity['id'],
                        'date' => $amenity['date'] ?? now(),
                        'status' => $amenity['status'] ?? 'pending',
                    ]);
                }
            }

            if ($request->has('billing')) {
                BillingTable::create([
                    'totalamount' => $request->billing['totalamount'],
                    'datebilled'  => $request->billing['datebilled'],
                    'status'      => $request->billing['status'],
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);
            }

            DB::commit();

            Log::info("✅ store success", [
                'request' => $request->all(),
                'created' => $booking->toArray(),
            ]);

            return response()->json($booking, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Store booking failed", [
                'request' => $request->all(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ✅ PUT update booking + related tables
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

            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            AmenityBookingTable::where('booking_id', $id)->delete();

            if ($request->has('rooms')) {
                foreach ($request->rooms as $roomID) {
                    RoomBookTable::create([
                        'bookingID' => $id,
                        'roomID' => $roomID,
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
                        'date' => $amenity['date'] ?? now(),
                        'status' => $amenity['status'] ?? 'pending',
                    ]);
                }
            }

            if ($request->has('billing')) {
                BillingTable::updateOrCreate(
                    ['bookingID' => $id],
                    [
                        'totalamount' => $request->billing['totalamount'],
                        'datebilled'  => $request->billing['datebilled'],
                        'status'      => $request->billing['status'],
                        'guestID'     => $booking->guestID,
                    ]
                );
            }

            DB::commit();

            Log::info("✅ update success", [
                'bookingID' => $id,
                'request'   => $request->all(),
                'updated'   => $booking->toArray(),
            ]);

            return response()->json($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Update booking failed", [
                'bookingID' => $id,
                'request'   => $request->all(),
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
