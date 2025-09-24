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

        $roomIDs = collect($source['roomBookings'] ?? [])->pluck('roomID')->toArray();
        $cottageIDs = collect($source['cottageBookings'] ?? [])->pluck('cottageID')->toArray();
        $menuIDs = collect($source['menuBookings'] ?? [])->pluck('menuID')->toArray();
        $menuQuantities = collect($source['menuBookings'] ?? [])->pluck('quantity')->toArray();

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
            foreach ($data['roomIDs'] as $roomID) {
                $rb = RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
                $roomIDs[] = $roomID;
                Log::info("📌 Room booked", ['bookingID' => $booking->bookingID, 'roomID' => $roomID, 'record' => $rb->toArray()]);
            }

            // Cottages
            $cottageIDs = [];
            foreach ($data['cottageIDs'] as $cottageID) {
                $cb = CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => now()->format('Y-m-d'),
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
                    'bookingDate' => now()->format('Y-m-d'),
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
            $roomIDs = [];
            foreach ($data['roomIDs'] as $roomID) {
                $rb = RoomBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'roomID'      => $roomID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
                $roomIDs[] = $roomID;
                Log::info("📌 Room booked", ['bookingID' => $booking->bookingID, 'roomID' => $roomID, 'record' => $rb->toArray()]);
            }

            $cottageIDs = [];
            foreach ($data['cottageIDs'] as $cottageID) {
                $cb = CottageBookTable::create([
                    'bookingID'   => $booking->bookingID,
                    'cottageID'   => $cottageID,
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
                $cottageIDs[] = $cottageID;
                Log::info("📌 Cottage booked", ['bookingID' => $booking->bookingID, 'cottageID' => $cottageID, 'record' => $cb->toArray()]);
            }

            $menuIDs = [];
            foreach ($data['menuIDs'] as $index => $menuID) {
                $mb = MenuBookingTable::create([
                    'booking_id'  => $booking->bookingID,
                    'menu_id'     => $menuID,
                    'quantity'    => $data['menuQuantities'][$index] ?? 1,
                    'status'      => 'Pending',
                    'bookingDate' => now()->format('Y-m-d'),
                ]);
                $menuIDs[] = $menuID;
                Log::info("📌 Menu booked", ['bookingID' => $booking->bookingID, 'menuID' => $menuID, 'record' => $mb->toArray()]);
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
                'roomIDs'     => $roomIDs,
                'cottageIDs'  => $cottageIDs,
                'menuIDs'     => $menuIDs,
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
