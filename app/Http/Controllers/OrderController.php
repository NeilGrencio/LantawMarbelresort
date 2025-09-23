<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\MenuTable;
use App\Models\OrderTable;
use App\Models\MenuBookingTable;
use Illuminate\Http\Request;
use PDO;

class OrderController extends Controller
{
    public function viewMenu() {
        $menu = MenuTable::where("status", 'Available')->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();
        return view('receptionist.order', compact('menu', 'uniqueMenuTypes'));
    }

    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'order'     => 'required|integer|exists:menu,menuID',
            'quantity'  => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();

            $guest = GuestTable::where('firstname', $validated['firstname'])
                ->where('lastname', $validated['lastname'])
                ->first();

            if (!$guest) {
                return redirect()->back()->with('error', 'Guest not found.');
            }

            $booking = BookingTable::where('guestID', $guest->guestID)->latest()->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'No booking found for this guest.');
            }

            $order = MenuBookingTable::create([
                'menu_id'     => $validated['order'],
                'booking_id'  => $booking->bookingID,
                'quantity'    => $validated['quantity'],
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
                'bookingDate' => $booking->bookingstart,
            ]);

            DB::commit();
            return redirect()->route('receptionist.order')
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Order was not created! ' . $e->getMessage());
        }
    }

    public function prepareOrder(MenuBookingTable $order)
    {
        $order->status = 'confirmed';
        $order->save();
    
        return redirect()->back()->with('success', 'Order marked as Confirmed.');
    }

}
