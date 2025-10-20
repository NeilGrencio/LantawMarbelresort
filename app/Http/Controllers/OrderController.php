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
use App\Models\SessionLogTable;
use App\Models\BillingTable;
use Illuminate\Http\Request;
use PDO;

class OrderController extends Controller
{   
    public function orderList(){
        $orders = MenuBookingTable::where('menu_bookings.status', '!=', 'Finished')
            ->whereDate('menu_bookings.created_at', Carbon::today())
            ->join('booking', 'menu_bookings.booking_id', '=', 'booking.bookingID')
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->join('menu', 'menu_bookings.menu_id', '=', 'menu.menuID')
            ->select(
                'menu_bookings.id as id',
                'menu_bookings.booking_id',
                'booking.bookingID',
                DB::raw("CONCAT('#', LPAD(menu_bookings.id, 3, '0')) as bookingTicket"),
                'menu.menuname',
                'menu.itemtype',
                'menu_bookings.quantity',
                DB::raw('menu_bookings.price * menu_bookings.quantity as total'),
                'menu_bookings.status',
                'menu_bookings.created_at'
            )
            ->orderBy('menu_bookings.created_at', 'desc')
            ->paginate(10);
        
        return view('receptionist.orderlist', compact('orders'));
    }
    public function viewMenu() {
        $menu = MenuTable::where("status", 'Available')
        ->where("itemtype", '!=', 'services')
        ->get();
        
        foreach ($menu as $item) {
            $item->image_url = $item->image
                ? route('menu.image', ['filename' => basename($item->image)])
                : asset('images/no-image.png');
        }
        
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();
        $guest = BookingTable::join('guest', 'booking.guestID', 'guest.guestID')
            ->whereDate('bookingstart', Carbon::today())
            ->select(
                'booking.*',
                'guest.firstname',
                'guest.lastname',
            )
            ->get();
        return view('receptionist.order', compact('menu', 'uniqueMenuTypes', 'guest'));
    }

    public function viewService(){
        $menu = MenuTable::where("status", 'Available')
        ->where("itemtype", 'services')
        ->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();
        $guest = BookingTable::join('guest', 'booking.guestID', 'guest.guestID')
            ->whereDate('bookingstart', Carbon::today())
            ->select(
                'booking.*',
                'guest.firstname',
                'guest.lastname',
            )
            ->get();
        return view('receptionist.order_service', compact('menu', 'uniqueMenuTypes', 'guest'));
    }

    public function editOrder(Request $request, $bookingID)
    {
        if ($request->isMethod('get')) {
            $menu = MenuTable::where("status", 'Available')->get();
            $uniqueMenuTypes = $menu->pluck('itemtype')->unique();

            $guest = BookingTable::join('guest', 'booking.guestID', '=', 'guest.guestID')
                ->where('booking.status', 'Ongoing')
                ->select('booking.*', 'guest.firstname', 'guest.lastname')
                ->get();

            $orders = MenuBookingTable::where('menu_bookings.booking_id', $bookingID)
                ->join('booking', 'menu_bookings.booking_id', '=', 'booking.bookingID')
                ->join('guest', 'booking.guestID', '=', 'guest.guestID')
                ->join('menu', 'menu_bookings.menu_id', '=', 'menu.menuID')
                ->select(
                    'menu_bookings.id as id',
                    'menu_bookings.menu_id',
                    'menu_bookings.booking_id',
                    DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                    'booking.bookingID',
                    DB::raw("CONCAT('#', LPAD(menu_bookings.id, 3, '0')) as bookingTicket"),
                    'menu.menuname',
                    'menu.itemtype',
                    'menu_bookings.quantity',
                    'menu_bookings.price',
                    DB::raw('(menu_bookings.price * menu_bookings.quantity) as total'),
                    'menu_bookings.status',
                    'menu_bookings.created_at',
                    'menu_bookings.updated_at',
                    'booking.bookingstart as bookingStart',
                    'booking.bookingend as bookingEnd'
                )
                ->orderBy('menu_bookings.created_at', 'desc')
                ->get();

            $guestName = $orders->isNotEmpty() ? $orders->first()->guestname : '';

            // Define variables for Blade
            $bookingstart = $orders->isNotEmpty() ? $orders->first()->bookingstart : null;
            $bookingend   = $orders->isNotEmpty() ? $orders->first()->bookingend : null;

            return view('receptionist.edit_order', compact(
                'menu', 
                'uniqueMenuTypes', 
                'guest', 
                'orders', 
                'bookingID', 
                'guestName',
                'bookingstart',
                'bookingend'
            ));
        }


        $validated = $request->validate([
            'order'       => 'nullable|array',
            'order.*'     => 'integer|exists:menu,menuID',
            'date'        =>  'date|required',
            'quantity'    => 'nullable|array',
            'quantity.*'  => 'integer|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $booking = BookingTable::find($bookingID);
            if (!$booking) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Booking not found.');
            }

            $existingOrders = MenuBookingTable::where('booking_id', $bookingID)
                ->pluck('menu_id')
                ->toArray();

            $grandTotal = 0;
            $processedItems = 0;
            $missingItems = [];
            $newMenuIds = $validated['order'] ?? [];

            foreach ($newMenuIds as $index => $menuId) {
                $menu = MenuTable::find($menuId);
                if (!$menu) {
                    $missingItems[] = $menuId;
                    continue;
                }

                $quantity = $validated['quantity'][$index] ?? 0;
                
                if ($quantity <= 0) {
                    continue;
                }

                $pricePerUnit = $menu->price;
                $totalPrice = $pricePerUnit * $quantity;
                $grandTotal += $totalPrice;

                $orderDate = Carbon::parse($validated['date'])->format('Y-m-d H:i:s');

                MenuBookingTable::updateOrCreate(
                    [
                        'booking_id' => $bookingID,
                        'menu_id'    => $menuId,
                    ],
                    [
                        'quantity'   => $quantity,
                        'price'      => $pricePerUnit,
                        'orderdate'  => $orderDate,
                        'status'     => 'Pending',
                        'updated_at' => now(),
                    ]
                );

                $processedItems++;
            }

            $itemsToRemove = array_diff($existingOrders, $newMenuIds);
            if (!empty($itemsToRemove)) {
                MenuBookingTable::where('booking_id', $bookingID)
                    ->whereIn('menu_id', $itemsToRemove)
                    ->delete();
            }

            foreach ($newMenuIds as $index => $menuId) {
                $quantity = $validated['quantity'][$index] ?? 0;
                if ($quantity <= 0) {
                    MenuBookingTable::where('booking_id', $bookingID)
                        ->where('menu_id', $menuId)
                        ->delete();
                }
            }

            if ($processedItems > 0 && $grandTotal > 0) {
                $billing = BillingTable::firstOrNew(['bookingID' => $bookingID]);
                
                $existingBillingAmount = 0;
                if ($billing->exists) {
                    $existingBillingAmount = $billing->totalamount ?? 0;
                    
                    $oldMenuTotal = MenuBookingTable::where('booking_id', $bookingID)
                        ->join('menu', 'menu_bookings.menu_id', '=', 'menu.menuID')
                        ->sum(DB::raw('menu_bookings.quantity * menu_bookings.price'));
                    
                    $existingBillingAmount -= $oldMenuTotal;
                }

                $billing->fill([
                    'guestID'      => $booking->guestID,
                    'totalamount'  => max(0, $existingBillingAmount + $grandTotal),
                    'datebilled'   => now(),
                    'status'       => 'Unpaid',
                ])->save();
            } elseif ($processedItems === 0) {
                $billing = BillingTable::where('bookingID', $bookingID)->first();
                if ($billing) {
                    $billing->totalamount = 0;
                    $billing->save();
                }
            }

            DB::commit();

            $message = 'Order updated successfully.';
            if (!empty($missingItems)) {
                $message .= ' However, some items were not found in the menu.';
            }

            return redirect()->route('receptionist.editorder', $bookingID)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string',
            'order'      => 'required|array',
            'order.*'    => 'integer|exists:menu,menuID',
            'date'       => 'required|date',
            'quantity'   => 'required|array',
            'quantity.*' => 'integer|min:1|max:10',
        ]);

        DB::beginTransaction();

        try {
            $guestName = trim($validated['guest_name']);
            $lastSpace = strrpos($guestName, ' ');

            if ($lastSpace !== false) {
                $firstname = substr($guestName, 0, $lastSpace);
                $lastname = substr($guestName, $lastSpace + 1);
            } else {
                $firstname = $guestName;
                $lastname = '';
            }

            $guest = GuestTable::where('firstname', $firstname)
                ->where('lastname', $lastname)
                ->firstOrFail();

            $today = Carbon::today();
            $booking = BookingTable::where('guestID', $guest->guestID)
                ->orWhere('status', 'ongoing')
                ->whereDate('bookingstart', '<=', $today)
                ->whereDate('bookingend', '>=', $today)
                ->firstOrFail();

            $grandTotal = 0;
            $createdOrders = [];

            $orderDate = Carbon::parse($validated['date'])->format('Y-m-d H:i:s');

            foreach ($validated['order'] as $index => $menuId) {
                $quantity = $validated['quantity'][$index];
                $menu = MenuTable::findOrFail($menuId);
                $itemPrice = $menu->price * $quantity;

                $order = MenuBookingTable::create([
                    'menu_id'     => $menuId,
                    'booking_id'  => $booking->bookingID,
                    'quantity'    => $quantity,
                    'price'       => $itemPrice,
                    'orderdate' => $orderDate,
                    'bookingDate' => $booking->bookingstart,
                    'status'      => 'Pending',
                ]);

                $grandTotal += $itemPrice;
                $createdOrders[] = $order;
            }

            if ($createdOrders) {
                $billing = BillingTable::firstOrNew(['bookingID' => $booking->bookingID]);
                $billing->totalamount = ($billing->totalamount ?? 0) + $grandTotal;
                $billing->datebilled = now();
                $billing->status = 'Unpaid';
                $billing->guestID = $guest->guestID;
                $billing->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit order: ' . $e->getMessage());
        }
    }

    public function viewMenuKitchen(){
        $menu = MenuTable::where("status", 'Available')->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();
        return view('kitchenstaff.menu', compact('menu', 'uniqueMenuTypes'));
    }
    public function prepareOrder($order, Request $request)
    {
        $menuOrder = MenuBookingTable::find($order);

        if ($menuOrder) {
            $menuOrder->update(['status' => 'Confirmed']);
        } else {
            MenuBookingTable::where('booking_id', $order)
                ->where('status', 'pending')
                ->update(['status' => 'Confirmed']);
        }
        
        $userID = $request->session()->get('user_id');

        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User prepared an order: ',
                'date'     => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Order is now being prepared.');
    }

    public function serveOrder($order, Request $request)
    {
        $menuOrder = MenuBookingTable::find($order);

        if ($menuOrder) {
            $menuOrder->update(['status' => 'finished']);
        } else {

            MenuBookingTable::where('booking_id', $order)
                ->where('status', 'Confirmed')
                ->update(['status' => 'finished']);
        }

        $userID = $request->session()->get('user_id');

        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User served an order: ',
                'date'     => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Order have been served.');
    }

}
