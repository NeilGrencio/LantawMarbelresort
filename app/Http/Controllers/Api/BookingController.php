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
            'guestamount'      => $source['guestamount'] ?? $source['guestAmount'] ?? 0,
            'childguest'       => $source['childguest'] ?? $source['childGuest'] ?? 0,
            'adultguest'       => $source['adultguest'] ?? $source['adultGuest'] ?? 0,
            'totalprice'       => $source['totalprice'] ?? $source['totalPrice'] ?? 0,
            'bookingstart'     => $this->parseDate($source['bookingstart'] ?? $source['bookingStart'] ?? null),
            'bookingend'       => $this->parseDate($source['bookingend'] ?? $source['bookingEnd'] ?? null),
            'status'           => $source['status'] ?? null,
            'guestID'          => $source['guestID'] ?? null,
            'amenityID'        => $source['amenityID'] ?? ($source['amenity']['amenityID'] ?? null),

            // relations
            'roomBookings'     => $source['roomBookings'] ?? $source['room_bookings'] ?? [],
            'cottageBookings'  => $source['cottageBookings'] ?? $source['cottage_bookings'] ?? [],
            'menuBookings'     => $source['menuBookings'] ?? $source['menu_bookings'] ?? [],
            'billing'          => $source['billing'] ?? null,
        ];
    }

    // ✅ POST create booking
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $this->normalize($request);

            $booking = BookingTable::create([
                'guestamount'    => $data['guestamount'],
                'childguest'     => $data['childguest'],
                'adultguest'     => $data['adultguest'],
                'totalprice'     => $data['totalprice'],
                'bookingcreated' => now()->format('Y-m-d'),
                'bookingend'     => $data['bookingend'],
                'bookingstart'   => $data['bookingstart'],
                'booking_type'   => $data['booking_type'],
                'status'         => $data['status'] ?? 'Pending',
                'guestID'        => $data['guestID'],
                'amenityID'      => $data['amenityID']
            ]);

            foreach ($data['roomBookings'] as $room) {
                $roomID = !empty($room['roomID']) && $room['roomID'] > 0
                    ? $room['roomID']
                    : ($room['room']['roomID'] ?? null);

                if ($roomID) { // only create if valid
                    $bookingDate = $this->parseDate($room['bookingDate'] ?? null);
                    RoomBookTable::create([
                        'bookingID'   => $booking->bookingID ?? $id,
                        'roomID'      => $roomID,
                        'price'       => $room['price'] ?? ($room['room']['price'] ?? 0),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Cottages
            foreach ($data['cottageBookings'] as $cottage) {
                $cottageID = !empty($cottage['cottageID']) && $cottage['cottageID'] > 0
                    ? $cottage['cottageID']
                    : ($cottage['cottage']['cottageID'] ?? null);

                if ($cottageID) {
                    $bookingDate = $this->parseDate($cottage['bookingDate'] ?? null);
                    CottageBookTable::create([
                        'bookingID'   => $booking->bookingID ?? $id,
                        'cottageID'   => $cottageID,
                        'price'       => $cottage['price'] ?? ($cottage['cottage']['price'] ?? 0),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Menus
            foreach ($data['menuBookings'] as $menu) {
                $menuID = !empty($menu['menuID']) && $menu['menuID'] > 0
                    ? $menu['menuID']
                    : ($menu['menu']['menuID'] ?? null);

                if ($menuID) {
                    $bookingDate = $this->parseDate($menu['bookingDate'] ?? null);
                    MenuBookingTable::create([
                        'booking_id'  => $booking->bookingID ?? $id,
                        'menu_id'     => $menuID,
                        'quantity'    => $menu['quantity'] ?? ($menu['menu']['qty'] ?? 1),
                        'price'       => $menu['price'] ?? ($menu['menu']['price'] ?? 0),
                        'status'      => $menu['status'] ?? ($menu['menu']['status'] ?? 'Pending'),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Billing + Payments
            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);

                if (!empty($data['billing']['payments'])) {
                    foreach ($data['billing']['payments'] as $payment) {
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
        DB::beginTransaction();
        try {
            $booking = BookingTable::findOrFail($id);
            $data = $this->normalize($request);

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

            // ✅ Clear old related data
            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete();

            foreach ($data['roomBookings'] as $room) {
                $roomID = !empty($room['roomID']) && $room['roomID'] > 0
                    ? $room['roomID']
                    : ($room['room']['roomID'] ?? null);

                if ($roomID) { // only create if valid
                    $bookingDate = $this->parseDate($room['bookingDate'] ?? null);
                    RoomBookTable::create([
                        'bookingID'   => $booking->bookingID ?? $id,
                        'roomID'      => $roomID,
                        'price'       => $room['price'] ?? ($room['room']['price'] ?? 0),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Cottages
            foreach ($data['cottageBookings'] as $cottage) {
                $cottageID = !empty($cottage['cottageID']) && $cottage['cottageID'] > 0
                    ? $cottage['cottageID']
                    : ($cottage['cottage']['cottageID'] ?? null);

                if ($cottageID) {
                    $bookingDate = $this->parseDate($cottage['bookingDate'] ?? null);
                    CottageBookTable::create([
                        'bookingID'   => $booking->bookingID ?? $id,
                        'cottageID'   => $cottageID,
                        'price'       => $cottage['price'] ?? ($cottage['cottage']['price'] ?? 0),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Menus
            foreach ($data['menuBookings'] as $menu) {
                $menuID = !empty($menu['menuID']) && $menu['menuID'] > 0
                    ? $menu['menuID']
                    : ($menu['menu']['menuID'] ?? null);

                if ($menuID) {
                    $bookingDate = $this->parseDate($menu['bookingDate'] ?? null);
                    MenuBookingTable::create([
                        'booking_id'  => $booking->bookingID ?? $id,
                        'menu_id'     => $menuID,
                        'quantity'    => $menu['quantity'] ?? ($menu['menu']['qty'] ?? 1),
                        'price'       => $menu['price'] ?? ($menu['menu']['price'] ?? 0),
                        'status'      => $menu['status'] ?? ($menu['menu']['status'] ?? 'Pending'),
                        'bookingDate' => $bookingDate,
                    ]);
                }
            }

            // ✅ Update billing + payments
            if ($data['billing']) {
                $billing = BillingTable::updateOrCreate(
                    ['bookingID' => $id],
                    [
                        'totalamount' => $data['billing']['totalamount'] ?? 0,
                        'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
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
                            'datepayment' => $this->parseDate($payment['datepayment'] ?? now()),
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

    // ✅ Helper: parse date safely into DATE only
    private function parseDate($date)
    {
        if (!$date) return null;
        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
