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

class BookingController extends Controller
{
    // ✅ GET all bookings by guestID
    public function getByGuest($guestID)
    {
        $bookings = BookingTable::with([
            'Guest',
            'AmenityBook.amenity',
            'roomBookings.Room',
            'cottageBookings.Cottage',
            'billing.payments',
            'billing.charge',
            'billing.guest'
        ])->where('guestID', $guestID)->get();

        // Force all string values into valid UTF-8
        $cleaned = $bookings->map(function ($booking) {
            return collect($booking->toArray())->map(function ($value) {
                return is_string($value)
                    ? mb_convert_encoding($value, 'UTF-8', 'UTF-8')
                    : $value;
            });
        });

        return response()->json($cleaned, 200, [], JSON_UNESCAPED_UNICODE);
    }

    // ✅ GET booking by bookingID
    public function show($id)
    {
        $booking = BookingTable::with([
            'Guest',
            'AmenityBook.amenity',
            'roomBookings.Room',
            'cottageBookings.Cottage',
            'billing.payments',
            'billing.charge',
            'billing.guest'
        ])->findOrFail($id);

        return response()->json($booking);
    }

    // ✅ POST create booking + related tables
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Create booking
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

            // 2. Save room bookings
            if ($request->has('rooms')) {
                foreach ($request->rooms as $roomID) {
                    RoomBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'roomID' => $roomID,
                    ]);
                }
            }

            // 3. Save cottage bookings
            if ($request->has('cottages')) {
                foreach ($request->cottages as $cottageID) {
                    CottageBookTable::create([
                        'bookingID' => $booking->bookingID,
                        'cottageID' => $cottageID,
                    ]);
                }
            }

            // 4. Save amenity bookings
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

            // 5. Save billing
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
            return response()->json($booking, 201);
        } catch (\Exception $e) {
            DB::rollBack();
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

            // Update related tables (simple approach: delete & reinsert)
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
            return response()->json($booking);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
