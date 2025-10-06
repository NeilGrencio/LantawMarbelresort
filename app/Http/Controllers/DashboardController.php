<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\BookingTable;
use App\Models\QRTable;
use App\Models\RoomBookTable;
use App\Models\PaymentTable;
use App\Models\ChatTable;
use App\Models\SessionLogTable;
use App\Models\RoomTable;
use App\Models\CottageTable;
use App\Models\FeedbackTable;
use App\Models\MenuBookingTable;

class DashboardController extends BaseAuthController
{
    public function managerDashboard(Request $request)
    {
        $filterType = $request->query('filterType', 'year');
        $year       = $request->query('year', Carbon::now()->year);

        if ($filterType === 'year') {
            $start = Carbon::create($year)->startOfYear();
            $end   = Carbon::create($year)->endOfYear();
        } elseif ($filterType === 'month') {
            $start = Carbon::create($year, Carbon::now()->month)->startOfMonth();
            $end   = Carbon::create($year, Carbon::now()->month)->endOfMonth();
        } elseif ($filterType === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end   = Carbon::now()->endOfWeek();
        } else {
            $start = Carbon::now()->subMonths(11)->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
        }

        $bookings = BookingTable::select(
            DB::raw($filterType === 'year'
                ? "DATE_FORMAT(bookingcreated, '%b') as period"
                : ($filterType === 'month'
                    ? "DATE(bookingcreated) as period"
                    : "DAYNAME(bookingcreated) as period")),
            DB::raw("COUNT(*) as bookingTotal")
        )
        ->whereBetween('bookingcreated', [$start, $end])
        ->groupBy('period')
        ->orderByRaw("MIN(bookingcreated)")
        ->get();

        $daytour = QRTable::select(
            DB::raw($filterType === 'year'
                ? "DATE_FORMAT(accessdate, '%b') as period"
                : ($filterType === 'month'
                    ? "DATE(accessdate) as period"
                    : "DAYNAME(accessdate) as period")),
            DB::raw("COUNT(*) as daytourTotal")
        )
        ->whereBetween('accessdate', [$start, $end])
        ->groupBy('period')
        ->orderByRaw("MIN(accessdate)")
        ->get();

        $roomBookings = RoomBookTable::join('rooms', 'rooms.roomID', '=', 'roombook.roomID')
            ->join('booking', 'booking.bookingID', '=', 'roombook.bookingID')
            ->select('rooms.roomnum', DB::raw('COUNT(*) as totalBookings'))
            ->whereBetween('booking.bookingcreated', [$start, $end])
            ->groupBy('rooms.roomnum')
            ->get();

        $amenityAccess = QRTable::join('amenities', 'amenities.amenityID', '=', 'qrcodes.amenityID')
            ->select(
                'amenities.amenityname',
                DB::raw("COUNT(*) as totalAccess")
            )
            ->whereBetween('accessdate', [$start, $end])
            ->groupBy('amenities.amenityID', 'amenities.amenityname')
            ->get();

        $revenueData = PaymentTable::select(
            DB::raw($filterType === 'year'
                ? "DATE_FORMAT(datepayment, '%b') as period"
                : ($filterType === 'month'
                    ? "DATE(datepayment) as period"
                    : "DAYNAME(datepayment) as period")),
            DB::raw("SUM(totaltender - totalchange) as totalRevenue")
        )
        ->whereBetween('datepayment', [$start, $end])
        ->groupBy('period')
        ->orderByRaw("MIN(datepayment)")
        ->get();

        $feedbackNotification = FeedbackTable::where('status', 'Unread')->count();

        $notificationInquiry = ChatTable::where('status', 'Unread')
            ->distinct('guestID')
            ->count('guestID');

        $todayStart = Carbon::now()->startOfDay();
        $todayEnd   = Carbon::now()->endOfDay();
        $userLogIns = SessionLogTable::whereBetween('date', [$todayStart, $todayEnd])->count();

        $availableRooms     = RoomTable::where('status', 'Available')->count();
        $unavailableRooms   = RoomTable::where('status', 'Unavailable')->count();
        $maintenancedRooms  = RoomTable::where('status', 'Under Maintenance')->count();

        $availableCottages     = CottageTable::where('status', 'Available')->count();
        $unavailableCottages   = CottageTable::where('status', 'Unavailable')->count();
        $maintenancedCottages  = CottageTable::where('status', 'Under Maintenance')->count();


        $revenueLabels = $revenueData->pluck('period')->toArray();
        $revenueValues = $revenueData->pluck('totalRevenue')->toArray();

        $amenityLabels = $amenityAccess->pluck('amenityname')->toArray();
        $amenityData   = $amenityAccess->pluck('totalAccess')->toArray();

        $bookingLabels = $bookings->pluck('period')->toArray();
        $bookingData   = $bookings->pluck('bookingTotal')->toArray();

        $daytourLabels = $daytour->pluck('period')->toArray();
        $daytourData   = $daytour->pluck('daytourTotal')->toArray();

        $roomlabels    = $roomBookings->pluck('roomnum')->toArray();
        $roombookData  = $roomBookings->pluck('totalBookings')->toArray();


        return view('manager.dashboard', compact(
            'filterType', 'year',
            'feedbackNotification', 'notificationInquiry', 'userLogIns',
            'availableRooms', 'unavailableRooms', 'maintenancedRooms',
            'availableCottages', 'unavailableCottages', 'maintenancedCottages',
            'revenueLabels', 'revenueValues',
            'amenityLabels', 'amenityData',
            'bookingLabels', 'bookingData',
            'daytourLabels', 'daytourData',
            'roomlabels', 'roombookData'
        ));
    }

    /**
     * Receptionist Dashboard
     */
   public function receptionistDashboard()
    {   
        $pendingBook = BookingTable::where('status', 'Pending')
            ->whereDate('bookingstart', Carbon::today())
            ->count();

        $dueBooking = BookingTable::where('status', 'Pending') 
            ->whereDate('bookingend', Carbon::today())
            ->count();

        $cancelledBook = BookingTable::where('status', 'Cancelled')
            ->whereDate('bookingstart', Carbon::today())
            ->count();

        $chats = ChatTable::where('status', 'Unread')
            ->distinct('guestID')
            ->count('guestID');

        $checkInToday = BookingTable::whereDate('bookingstart', Carbon::today())->count();
        $checkOutToday = BookingTable::whereDate('bookingend', Carbon::today())->count();
        $totalRooms = RoomTable::count();
        $currentOccupied = QRTable::whereDate('accessdate', Carbon::today())
            ->distinct('guestID')
            ->count('guestID');

        return view('receptionist.dashboard', compact(
            'chats',
            'pendingBook', 
            'dueBooking', 
            'cancelledBook', 
            'checkInToday', 
            'checkOutToday', 
            'totalRooms', 
            'currentOccupied'
        ));
    }

    public function kitchenDashboard()
    {
    $orders = MenuBookingTable::join('booking', 'menu_bookings.booking_id', '=', 'booking.bookingID')
        ->join('guest', 'booking.guestID', '=', 'guest.guestID')
        ->join('menu', 'menu_bookings.menu_id', '=', 'menu.menuID')
        ->select(
            'menu_bookings.*',
            'guest.firstname',
            'guest.lastname',
            'menu.menuname',
            'menu.itemtype',
            DB::raw('menu_bookings.price * menu_bookings.quantity as total')
        )
        ->orderBy('menu_bookings.created_at', 'desc')
        ->paginate(10);

        return view('kitchenstaff.dashboard', compact('orders'));
    }

}
