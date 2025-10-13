<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTable;
use Illuminate\Support\Facades\DB;
use App\Models\BookingTable;
use App\Models\QRTable;
use App\Models\AmenityTable;
use Carbon\Carbon;

class SearchUserController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        $users = DB::table('users')
            ->leftJoin('guest', 'users.userID', '=', 'guest.userID')
            ->leftJoin('staff', 'users.userID', '=', 'staff.userID')
            ->select(
                'users.*',
                'guest.firstname as g_firstname',
                'guest.lastname as g_lastname',
                'guest.role as g_role',
                'guest.avatar as g_avatar',
                'staff.firstname as s_firstname',
                'staff.lastname as s_lastname',
                'staff.role as s_role',
                'staff.avatar as s_avatar'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.username', 'like', "%{$search}%")
                    ->orWhere('users.userID', 'like', "%{$search}%")
                    ->orWhere('staff.firstname', 'like', "%{$search}%")
                    ->orWhere('staff.lastname', 'like', "%{$search}%")
                    ->orWhere('guest.firstname', 'like', "%{$search}%")
                    ->orWhere('guest.lastname', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(staff.firstname, ' ', staff.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(staff.lastname, ' ', staff.firstname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.lastname, ' ', guest.firstname)"), 'like', "%{$search}%");
                });
            })
            ->paginate(10);
            
            foreach ($users as $user) {
                $avatar = $user->s_avatar ?? $user->g_avatar;
        
                $user->image_url = $avatar
                    ? route('avatar.image', ['filename' => basename($avatar)])
                    : asset('images/profile.jpg');
            }

        return view('manager.user_list', compact('users'));
    }


    public function searchGuest(Request $request)
    {
        $search = $request->input('search');

        $guest = DB::table('guest')
            ->leftJoin('users', 'guest.userID', '=', 'users.userID')
            ->select(
                'guest.*',
                'users.username as username',
                'users.userID as userID'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.username', 'like', "%{$search}%")
                    ->orWhere('users.userID', 'like', "%{$search}%")
                    ->orWhere('guest.firstname', 'like', "%{$search}%")
                    ->orWhere('guest.lastname', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.lastname, ' ', guest.firstname)"), 'like', "%{$search}%");
                });
            })
            ->paginate(10);
            
            foreach ($guest as $g) {
                $avatar = $g->avatar ?? null;
            
                $g->image_url = !empty($avatar)
                    ? route('avatar.image', ['filename' => basename($avatar)])
                    : asset('images/profile.jpg');
            }

        return view('manager.guest_list', compact('guest'));
    }

    public function searchDiscount(Request $request)
    {
        $search = $request->input('search');

        $discount = DB::table('discount')
            ->select('discount.*', 'discount.amount as percentage') // alias
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->paginate(10);


        return view('manager.discount', compact('discount'));
    }

    public function searchRoom(Request $request)
    {
        $search = $request->input('search');

        $rooms = DB::table('rooms')
            ->select(
                'rooms.*'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('rooms.roomnum', 'like', "%{$search}%")
                    ->orWhere('rooms.roomtype', 'like', "%{$search}%")
                    ->orWhere('rooms.status', 'like', "%{$search}%")
                    ->orWhere('rooms.roomcapacity', 'like', "%{$search}%")
                    ->orWhere('rooms.price', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('manager.room_list', compact('rooms'));
    }

    public function searchAmenity(Request $request)
    {
        $search = $request->input('search');

        $amenities = DB::table('amenities')
            ->select(
                'amenities.*'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('amenities.amenityname', 'like', "%{$search}%")
                    ->orWhere('amenities.description', 'like', "%{$search}%")
                    ->orWhere('amenities.adultprice', 'like', "%{$search}%")
                    ->orWhere('amenities.childprice', 'like', "%{$search}%")
                    ->orWhere('amenities.status', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('manager.amenity_list', compact('amenities'));
    }

    public function searchCottage(Request $request)
    {
        $search = $request->input('search');

        $cottage = DB::table('cottages')
            ->select(
                'cottages.*'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cottages.cottagename', 'like', "%{$search}%")
                    ->orWhere('cottages.capacity', 'like', "%{$search}%")
                    ->orWhere('cottages.price', 'like', "%{$search}%")
                    ->orWhere('cottages.status', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('manager.cottage_list', compact('cottage'));
    }

    public function searchMenu(Request $request)
    {
        $search = $request->input('search');

        $menu = DB::table('menu')
            ->select('menu.*')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('menu.menuname', 'like', "%{$search}%")
                    ->orWhere('menu.itemtype', 'like', "%{$search}%")
                    ->orWhere('menu.price', 'like', "%{$search}%")
                    ->orWhere('menu.status', 'like', "%{$search}%");
                });
            })
            ->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique()->values();

        return view('manager.menu_list', compact('menu', 'uniqueMenuTypes', 'search'));
    }


    public function searchBooking(Request $request)
    {
        $search = $request->input('search');

        $bookings = BookingTable::with(['guest', 'roomBookings.room', 'cottageBookings.cottage'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('bookingID', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('bookingstart', 'like', "%{$search}%")
                        ->orWhere('bookingend', 'like', "%{$search}%")
                        ->orWhere('totalprice', 'like', "%{$search}%")
                        ->orWhereHas('guest', function ($guestQ) use ($search) {
                            $guestQ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(10);

        return view('receptionist.booking_list', compact('bookings'));
    }

    public function searchDaytour(Request $request)
    {
        $today = Carbon::now()->toDateString();
        $search = $request->input('search');

        $amenity = AmenityTable::get();
        $recent = QRTable::with(['guest', 'amenity'])
                    ->whereDate('accessdate', $today)
                    ->orderBy('qrID', 'desc')
                    ->get();

        $qrcode = QRTable::with(['guest', 'amenity'])
                    ->orderBy('qrID', 'desc')
                    ->get();

        $daytours = QRTable::with(['guest', 'amenity'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('qrID', 'like', "%{$search}%")
                    ->orWhere('qrcode', 'like', "%{$search}%")
                    ->orWhere('accessdate', 'like', "%{$search}%")
                    ->orWhereHas('guest', function ($guestQ) use ($search) {
                        $guestQ->where('firstname', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%");
                    })
                    ->orWhereHas('amenity', function ($amenityQ) use ($search) {
                        $amenityQ->where('amenityname', 'like', "%{$search}%");
                    });
                });
            })
            ->paginate(10);

        return view('receptionist.daytourDashboard', compact('amenity', 'qrcode', 'recent', 'daytours'));
    }

    public function searchGuestReceptionist(Request $request)
    {
        $search = $request->input('search');

        $guest = DB::table('guest')
            ->leftJoin('users', 'guest.userID', '=', 'users.userID')
            ->select(
                'guest.*',
                'users.username as username',
                'users.userID as userID'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.username', 'like', "%{$search}%")
                    ->orWhere('users.userID', 'like', "%{$search}%")
                    ->orWhere('guest.firstname', 'like', "%{$search}%")
                    ->orWhere('guest.lastname', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.lastname, ' ', guest.firstname)"), 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('receptionist.guest_list_receptionist', compact('guest'));
    }

    public function searchBilling(Request $request)
    {
        $search = $request->input('search');

        $payments = DB::table('payment')
            ->leftJoin('guest', 'payment.guestID', '=', 'guest.guestID')
            ->leftJoin('billing', 'payment.billingID', '=', 'billing.billingID')
            ->select(
                'payment.paymentID',
                'payment.totaltender',
                'payment.totalchange',
                'payment.datepayment',
                'payment.guestID',
                'payment.billingID',
                'billing.totalamount',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname")
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payment.paymentID', 'like', "%{$search}%")
                    ->orWhere('payment.totaltender', 'like', "%{$search}%")
                    ->orWhere('payment.totalchange', 'like', "%{$search}%")
                    ->orWhere('payment.datepayment', 'like', "%{$search}%")
                    ->orWhere('billing.totalamount', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.lastname, ' ', guest.firstname)"), 'like', "%{$search}%");
                });
            })
            ->orderBy('payment.paymentID', 'desc')
            ->paginate(10);

        return view('receptionist.billing', compact('payments'));
    }

    public function searchMenuReceptionist(Request $request)
    {
        $search = $request->input('search');

        $menu = DB::table('menu')
            ->select('menu.*')
            ->where('menu.itemtype', '!=', 'services')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('menu.menuname', 'like', "%{$search}%")
                    ->orWhere('menu.itemtype', 'like', "%{$search}%")
                    ->orWhere('menu.price', 'like', "%{$search}%")
                    ->orWhere('menu.status', 'like', "%{$search}%");
                });
            })
            ->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique()->values();
        $guest = BookingTable::join('guest', 'booking.guestID', 'guest.guestID')
            ->whereDate('bookingstart', Carbon::today())
            ->select(
                'booking.*',
                'guest.firstname',
                'guest.lastname',
            )
            ->get();
        return view('receptionist.order', compact('guest','menu', 'uniqueMenuTypes', 'search'));
    }

    public function searchServices(Request $request){
        $search = $request->input('search');

        $menu = DB::table('menu')
            ->select('menu.*')
            ->where('menu.itemtype', '=', 'services')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('menu.menuname', 'like', "%{$search}%")
                    ->orWhere('menu.itemtype', 'like', "%{$search}%")
                    ->orWhere('menu.price', 'like', "%{$search}%")
                    ->orWhere('menu.status', 'like', "%{$search}%");
                });
            })
            ->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique()->values();
        $guest = BookingTable::join('guest', 'booking.guestID', 'guest.guestID')
            ->whereDate('bookingstart', Carbon::today())
            ->select(
                'booking.*',
                'guest.firstname',
                'guest.lastname',
            )
            ->get();
        return view('receptionist.services', compact('guest', 'menu', 'uniqueMenuTypes', 'search'));
    }

    public function searchLogs(Request $request)
    {
        $search = $request->input('search');

        $session = DB::table('usersessionlog')
            ->join('users', 'usersessionlog.userID', '=', 'users.userID')
            ->select('usersessionlog.*', 'users.username')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('usersessionlog.sessionID', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%")
                    ->orWhere('usersessionlog.activity', 'like', "%{$search}%")
                    ->orWhere('usersessionlog.date', 'like', "%{$search}%")
                    ->orWhere('usersessionlog.userID', 'like', "%{$search}%");
                });
            })
            ->orderBy('usersessionlog.sessionID', 'desc')
            ->paginate(10);

        return view('manager.session_logs', compact('session'));
    }
    
    public function searchFeedback(Request $request)
    {
        $search = $request->input('search');

        $feedbacks = DB::table('feedback')
            ->leftJoin('guest', 'feedback.guestID', '=', 'guest.guestID')
            ->select(
                'feedback.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as fullname")
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('feedback.feedbackID', 'like', "%{$search}%")
                    ->orWhere('feedback.message', 'like', "%{$search}%")
                    ->orWhere('feedback.date', 'like', "%{$search}%")
                    ->orWhere('feedback.rating', 'like', "%{$search}%")
                    ->orWhere('feedback.status', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.lastname, ' ', guest.firstname)"), 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('manager.feedback', compact('feedbacks'));
    }

    public function searchCheckin(Request $request){
        $search = $request->input('search');
        $today = Carbon::today()->toDateString();

        $checkin = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Booked')
            ->whereDate('booking.bookingstart', '<=', $today)
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('booking.bookingID', 'like', "%{$search}%")
                    ->orWhere('booking.status', 'like', "%{$search}%")
                    ->orWhere('booking.bookingstart', 'like', "%{$search}%")
                    ->orWhere('booking.bookingend', 'like', "%{$search}%")
                    ->orWhere('booking.totalprice', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%");
                });
            })
            ->orderBy('booking.bookingstart', 'desc')
            ->paginate(10);

        return view('receptionist.checkin', compact('checkin', 'today'));
    }

    public function searchCheckout(Request $request){
        $search = $request->input('search');
        $today = Carbon::today()->toDateString();

        $checkout = BookingTable::with(['roomBookings.room', 'cottageBookings.cottage', 'billing'])
            ->join('guest', 'booking.guestID', '=', 'guest.guestID')
            ->select('booking.*', DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) AS guestname"))
            ->where('booking.status', 'Ongoing')
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('booking.bookingID', 'like', "%{$search}%")
                    ->orWhere('booking.status', 'like', "%{$search}%")
                    ->orWhere('booking.bookingstart', 'like', "%{$search}%")
                    ->orWhere('booking.bookingend', 'like', "%{$search}%")
                    ->orWhere('booking.totalprice', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(guest.firstname, ' ', guest.lastname)"), 'like', "%{$search}%");
                });
            })
            ->orderBy('booking.bookingend', 'desc')
            ->paginate(10);

        return view('receptionist.checkout', compact('checkout', 'today'));
    }

}
