<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MenuTable;
use App\Models\OrderTable;
use App\Models\GuestTable;
use App\Models\BookingTable;
use App\Models\MenuBookingTable;
use App\Models\BillingTable;
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
        'firstname'   => 'required|string',
        'lastname'    => 'required|string',
        'order'       => 'required|array',
        'order.*'     => 'integer|exists:menu,menuID',
        'quantity'    => 'required|array',
        'quantity.*'  => 'integer|min:1|max:10',
    ]);

    try {
        DB::beginTransaction();

        // Find guest
        $guest = GuestTable::where('firstname', $validated['firstname'])
            ->where('lastname', $validated['lastname'])
            ->first();

        if (!$guest) {
            return redirect()->back()->with('error', 'Guest not found.');
        }

        // Find latest booking
        $booking = BookingTable::where('guestID', $guest->guestID)
            ->orderBy('bookingstart', 'desc')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'No booking found for this guest.');
        }

        $grandTotal = 0;
        $createdOrders = [];

        // Loop through orders and create entries
        foreach ($validated['order'] as $index => $menuId) {
            $quantity = $validated['quantity'][$index];
            $menu = MenuTable::find($menuId);

            if ($menu) {
                $itemPrice = $menu->price * $quantity;
                $grandTotal += $itemPrice;

                $order = MenuBookingTable::create([
                    'menu_id'     => $menuId,
                    'booking_id'  => $booking->bookingID,
                    'quantity'    => $quantity,
                    'price'       => $itemPrice,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                    'bookingDate' => $booking->bookingstart,
                    'status'      => 'Pending',
                ]);

                $createdOrders[] = $order;
            }
        }

        // Billing logic
        if (!empty($createdOrders)) {
            $billing = BillingTable::where('bookingID', $booking->bookingID)->first();

            if ($billing) {
                // Update existing billing
                $billing->update([
                    'totalamount' => $billing->totalamount + $grandTotal,
                    'datebilled'  => now(),
                    'status'      => 'Unpaid',
                    'guestID'     => $guest->guestID,
                ]);
            } else {
                // Create new billing
                BillingTable::create([
                    'totalamount' => $grandTotal,
                    'datebilled'  => now(),
                    'status'      => 'Unpaid',
                    'bookingID'   => $booking->bookingID,
                    'orderID'     => null,
                    'amenityID'   => null,
                    'chargeID'    => null,
                    'discountID'  => null,
                    'guestID'     => $guest->guestID,
                ]);
            }
        }

        DB::commit(); // ✅ make sure changes are saved

        return redirect()->back()->with('success', 'Order submitted successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


    public function prepareOrder(MenuBookingTable $order)
    {
        $order->status = 'confirmed';
        $order->save();
    
        return redirect()->back()->with('success', 'Order marked as Confirmed.');
    }

}
