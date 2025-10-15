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
    // GET all bookings by guestID
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
            ])->where('guestID', $guestID)->get();

            return response()->json($bookings, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ getByGuest failed", [
                'guestID' => $guestID,
                'error'   => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
public function showForEdit($id)
{
    try {
        $booking = BookingTable::with([
            'guest',
            'amenity',
            'rooms.room',          // assuming relationship roomBookTable->room
            'cottages.cottage',    // cottageBookTable->cottage
            'menus.menu',          // menuBookingTable->menu
            'billing.payments',    // billingTable->payments
        ])->findOrFail($id);

        // 🔄 Reconstruct original request-like structure
        $response = [
            'guestID'       => $booking->guestID,
            'childGuest'    => $booking->childguest,
            'adultGuest'    => $booking->adultguest,
            'totalPrice'    => $booking->totalprice,
            'bookingStart'  => $booking->bookingstart,
            'bookingEnd'    => $booking->bookingend,
            'status'        => $booking->status,
            'amenity'       => $booking->amenity ? [
                'amenityID'   => $booking->amenity->amenityID,
                'amenityName' => $booking->amenity->name ?? null,
            ] : null,

            // 🏠 Room bookings
            'roomBookings' => $booking->rooms->map(function ($r) {
                return [
                    'roomID'      => $r->roomID,
                    'bookingDate' => $r->bookingDate,
                    'roomName'    => $r->room->roomName ?? null,
                ];
            }),

            // 🏕 Cottage bookings
            'cottageBookings' => $booking->cottages->map(function ($c) {
                return [
                    'cottageID'   => $c->cottageID,
                    'bookingDate' => $c->bookingDate,
                    'cottageName' => $c->cottage->cottageName ?? null,
                ];
            }),

            // 🍽 Menu bookings
            'menuBookings' => $booking->menus->map(function ($m) {
                return [
                    'menuID'      => $m->menu_id,
                    'quantity'    => $m->quantity,
                    'bookingDate' => $m->bookingDate,
                    'menuName'    => $m->menu->menuName ?? null,
                ];
            }),

            // 💳 Billing info
            'billing' => $booking->billing ? [
                'totalamount' => $booking->billing->totalamount,
                'datebilled'  => $booking->billing->datebilled,
                'status'      => $booking->billing->status,
                'payments'    => $booking->billing->payments->map(function ($p) {
                    return [
                        'totaltender' => $p->totaltender,
                        'totalchange' => $p->totalchange,
                        'datepayment' => $p->datepayment,
                        'refNumber'   => $p->refNumber,
                    ];
                }),
            ] : null,
        ];

        return response()->json($response, 200);

    } catch (\Exception $e) {
        Log::error('❌ Failed to fetch booking', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    // GET single booking
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

            return response()->json($booking, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error("❌ show booking failed", [
                'bookingID' => $id,
                'error'     => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function normalize(Request $request)
    {
        $source = $request->all();  // <<<<<< get the request payload

        $childguest = $source['childGuest'] ?? 0;
        $adultguest = $source['adultGuest'] ?? 0;
        $guestamount = $childguest + $adultguest;
        $roomBookings    = $source['roomBookings'] ?? [];
        $cottageBookings = $source['cottageBookings'] ?? [];
        $menuBookings    = $source['menuBookings'] ?? [];
        $menuQuantities = collect($source['menuBookings'] ?? [])->pluck('quantity')->toArray();
        $roomIDs       = collect($roomBookings)->pluck('roomID')->toArray();
        $roomDates     = collect($roomBookings)->pluck('bookingDate')->toArray();

        $cottageIDs    = collect($cottageBookings)->pluck('cottageID')->toArray();
        $cottageDates  = collect($cottageBookings)->pluck('bookingDate')->toArray();

        $menuIDs       = collect($menuBookings)->pluck('menuID')->toArray();
        $menuQuantities = collect($menuBookings)->pluck('quantity')->toArray();
        $menuDates     = collect($menuBookings)->pluck('bookingDate')->toArray();
        $amenityID = $source['amenity']['amenityID'] ?? null;

        $bookingstart = isset($source['bookingStart']) ? $this->parseDate($source['bookingStart']) : null;
        $bookingend   = isset($source['bookingEnd'])   ? $this->parseDate($source['bookingEnd'])   : null;

        $billing  = $source['billing'] ?? null;
        $payments = $billing['payments'] ?? [];

        $normalized = [
            'childguest'   => $childguest,
            'adultguest'   => $adultguest,
            'guestamount'  => $guestamount,
            'totalprice'   => $source['totalPrice'] ?? 0,
            'bookingstart' => $bookingstart,
            'bookingend'   => $bookingend,
            'roomIDs'      => $roomIDs,
            'cottageIDs'   => $cottageIDs,
            'menuIDs'      => $menuIDs,
            'menuQuantities' => $menuQuantities,
            'billing'      => $billing,
            'payments'     => $payments,
            'status'       => $source['status'] ?? 'Pending',
            'guestID'      => $source['guestID'] ?? null,
            'amenityID'    => $amenityID,
            'roomDates'    => $roomDates,
            'cottageDates' => $cottageDates,
            'menuDates'    => $menuDates,
        ];

        Log::info("🧹 Normalized data", $normalized);

        return $normalized;
    }


    private function parseDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d') : null;
    }

    // Create booking
    // Create booking
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Log the raw request data
            $rawData = $request->all();
            Log::info("📥 Raw booking request received", $rawData);

            // Normalize
            $data = $this->normalize($request);
            Log::info("🧹 Normalized booking data", $data);

            // Create booking
            $booking = BookingTable::create([
                'guestamount'    => $data['guestamount'],
                'childguest'     => $data['childguest'],
                'adultguest'     => $data['adultguest'],
                'totalprice'     => $data['totalprice'],
                'bookingstart'   => $data['bookingstart'],
                'bookingend'     => $data['bookingend'],
                'status'         => $data['status'],
                'guestID'        => $data['guestID'],
                'amenityID'      => $data['amenityID'],
                'bookingcreated' => now()->format('Y-m-d'),
            ]);
            Log::info("📌 Booking created", ['bookingID' => $booking->bookingID, 'data' => $booking->toArray()]);

            // Rooms
            $roomIDs = [];
            foreach ($data['roomIDs'] as $index => $roomID) {
                $rb = RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => $this->parseDate($data['roomDates'][$index] ?? now()),
                ]);
                $roomIDs[] = $roomID;
                Log::info("📌 Room booked", ['bookingID' => $booking->bookingID, 'roomID' => $roomID, 'record' => $rb->toArray()]);
            }

            // Cottages
            $cottageIDs = [];
            foreach ($data['cottageIDs'] as $index => $cottageID) {
                $cb = CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => $this->parseDate($data['cottageDates'][$index] ?? now()),
                ]);
                $cottageIDs[] = $cottageID;
                Log::info("📌 Cottage booked", ['bookingID' => $booking->bookingID, 'cottageID' => $cottageID, 'record' => $cb->toArray()]);
            }

            // Menus
            $menuIDs = [];
            foreach ($data['menuIDs'] as $index => $menuID) {
                $mb = MenuBookingTable::create([
                    'booking_id'  => $booking->bookingID,
                    'menu_id'     => $menuID,
                    'quantity'    => $data['menuQuantities'][$index] ?? 1,
                    'status'      => 'Pending',
                    'bookingDate' => $this->parseDate($data['menuDates'][$index] ?? now()),
                ]);
                $menuIDs[] = $menuID;
                Log::info("📌 Menu booked", ['bookingID' => $booking->bookingID, 'menuID' => $menuID, 'record' => $mb->toArray()]);
            }

            // Billing & Payments
            $billingID = null;
            $paymentIDs = [];
            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);
                $billingID = $billing->billingID;
                Log::info("📌 Billing created", ['billing' => $billing->toArray()]);

                foreach ($data['payments'] as $payment) {
                    $p = PaymentTable::create([
                        'totaltender' => $payment['totaltender'] ?? 0,
                        'totalchange' => $payment['totalchange'] ?? 0,
                        'datepayment' => $this->parseDate($payment['datepayment'] ?? now()),
                        'guestID'     => $booking->guestID,
                        'billingID'   => $billing->billingID,
                        'refNumber'   => $payment['refNumber'] ?? null,
                    ]);
                    $paymentIDs[] = $p->paymentID;
                    Log::info("📌 Payment created", ['payment' => $p->toArray()]);
                }
            }

            DB::commit();

            Log::info("✅ Booking stored successfully", [
                'bookingID'   => $booking->bookingID,
                'roomIDs'     => $roomIDs,
                'cottageIDs'  => $cottageIDs,
                'menuIDs'     => $menuIDs,
                'billingID'   => $billingID,
                'paymentIDs'  => $paymentIDs,
            ]);

            return response()->json(['bookingID' => $booking->bookingID], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ store booking failed", ['error' => $e->getMessage(), 'rawData' => $rawData]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update booking
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Log the raw request data
            $rawData = $request->all();
            Log::info("📥 Raw booking update request", $rawData);

            $data = $this->normalize($request);
            Log::info("🧹 Normalized booking update data", $data);

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
            Log::info("📌 Booking updated", ['bookingID' => $booking->bookingID, 'data' => $booking->toArray()]);

            // Delete old related records and log deleted data
            $deletedRooms = RoomBookTable::where('bookingID', $id)->pluck('roomID')->toArray();
            $deletedCottages = CottageBookTable::where('bookingID', $id)->pluck('cottageID')->toArray();
            $deletedMenus = MenuBookingTable::where('booking_id', $id)->pluck('menu_id')->toArray();
            $deletedBilling = BillingTable::where('bookingID', $id)->pluck('billingID')->toArray();
            $deletedPayments = PaymentTable::whereIn('billingID', $deletedBilling)->pluck('paymentID')->toArray();

            RoomBookTable::where('bookingID', $id)->delete();
            CottageBookTable::where('bookingID', $id)->delete();
            MenuBookingTable::where('booking_id', $id)->delete();
            PaymentTable::whereIn('billingID', $deletedBilling)->delete();
            BillingTable::where('bookingID', $id)->delete();

            Log::info("🗑️ Deleted old related records", [
                'rooms' => $deletedRooms,
                'cottages' => $deletedCottages,
                'menus' => $deletedMenus,
                'billing' => $deletedBilling,
                'payments' => $deletedPayments,
            ]);

            // Recreate bookings (rooms, cottages, menus, billing, payments)
            foreach ($data['roomIDs'] as $index => $roomID) {
                RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => $this->parseDate($data['roomDates'][$index] ?? now()),
                ]);
            }

            foreach ($data['cottageIDs'] as $index => $cottageID) {
                CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => $this->parseDate($data['cottageDates'][$index] ?? now()),
                ]);
            }

            foreach ($data['menuIDs'] as $index => $menuID) {
                MenuBookingTable::create([
                    'booking_id'  => $booking->bookingID,
                    'menu_id'     => $menuID,
                    'quantity'    => $data['menuQuantities'][$index] ?? 1,
                    'status'      => 'Pending',
                    'bookingDate' => $this->parseDate($data['menuDates'][$index] ?? now()),
                ]);
            }

            $billingID = null;
            $paymentIDs = [];
            if ($data['billing']) {
                $billing = BillingTable::create([
                    'totalamount' => $data['billing']['totalamount'] ?? 0,
                    'datebilled'  => $this->parseDate($data['billing']['datebilled'] ?? now()),
                    'status'      => $data['billing']['status'] ?? 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'guestID'     => $booking->guestID,
                ]);
                $billingID = $billing->billingID;
                Log::info("📌 Billing created", ['billing' => $billing->toArray()]);

                foreach ($data['payments'] as $payment) {
                    $p = PaymentTable::create([
                        'totaltender' => $payment['totaltender'] ?? 0,
                        'totalchange' => $payment['totalchange'] ?? 0,
                        'datepayment' => $this->parseDate($payment['datepayment'] ?? now()),
                        'guestID'     => $booking->guestID,
                        'billingID'   => $billing->billingID,
                        'refNumber'   => $payment['refNumber'] ?? null,
                    ]);
                    $paymentIDs[] = $p->paymentID;
                    Log::info("📌 Payment created", ['payment' => $p->toArray()]);
                }
            }

            DB::commit();

            Log::info("✅ Booking updated successfully", [
                'bookingID'   => $booking->bookingID,

                'billingID'   => $billingID,
                'paymentIDs'  => $paymentIDs,
            ]);

            return response()->json(['bookingID' => $booking->bookingID], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ update booking failed", ['error' => $e->getMessage(), 'rawData' => $rawData]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
