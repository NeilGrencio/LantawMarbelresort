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

class DashboardController extends BaseAuthController
{
    /**
     * Manager Dashboard
     */
   public function managerDashboard()
    {
        $start = Carbon::now()->subMonths(11)->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        $bookings = BookingTable::select(
            DB::raw("DATE_FORMAT(MIN(bookingcreated), '%b %Y') as month"), 
            DB::raw("COUNT(*) as bookingTotal")
        )
        ->whereBetween('bookingcreated', [$start, $end])
        ->groupBy(DB::raw("YEAR(bookingcreated), MONTH(bookingcreated)"))
        ->orderBy(DB::raw("MIN(bookingcreated)"))
        ->get();

        $daybookings = BookingTable::select(
                DB::raw("MIN(STR_TO_DATE(CONCAT(YEARWEEK(bookingcreated, 1), ' Monday'), '%X%V %W')) as week_start"),
                DB::raw("COUNT(*) as bookingTotal")
            )
            ->whereBetween('bookingcreated', [$start, $end])
            ->groupBy(DB::raw("YEARWEEK(bookingcreated, 1)"))
            ->orderBy('week_start')
            ->get();

        $daytour = QRTable::select(
                DB::raw("MIN(STR_TO_DATE(CONCAT(YEARWEEK(accessdate, 1), ' Monday'), '%X%V %W')) as week_start"),
                DB::raw("COUNT(*) as daytourTotal")
            )
            ->whereBetween('accessdate', [$start, $end])
            ->groupBy(DB::raw("YEARWEEK(accessdate, 1)"))
            ->orderBy('week_start')
            ->get();

        $roomBookings = RoomBookTable::join('rooms', 'rooms.roomID', '=', 'roombook.roomID')
            ->join('booking', 'booking.bookingID', '=', 'roombook.bookingID')
            ->select('rooms.roomnum', DB::raw('COUNT(*) as totalBookings'))
            ->whereBetween('booking.bookingcreated', [$start, $end])
            ->groupBy('rooms.roomnum')
            ->get();

        $amenityAccess = QRTable::join('amenities', 'amenities.amenityID', '=', 'qrcodes.amenityID')
            ->select('amenities.amenityname', DB::raw("YEAR(accessdate) as year"), DB::raw("MONTH(accessdate) as month"), DB::raw("COUNT(*) as totalAccess"))
            ->whereBetween('accessdate', [$start, $end])
            ->groupBy('amenities.amenityID', 'amenities.amenityname', DB::raw("YEAR(accessdate)"), DB::raw("MONTH(accessdate)"))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $revenueData = PaymentTable::select(
            DB::raw("DATE_FORMAT(datepayment, '%b %Y') as month"),
            DB::raw("SUM(totaltender - totalchange) as totalRevenue")
        )
        ->whereBetween('datepayment', [$start, $end])
        ->groupBy(DB::raw("DATE_FORMAT(datepayment, '%b %Y')"))
        ->orderBy(DB::raw("MIN(datepayment)"))
        ->get();

        
        $revenueLabels = $revenueData->pluck('month')->toArray();
        $revenueValues = $revenueData->pluck('totalRevenue')->toArray();
        $amenityLabels = $amenityAccess->pluck('amenityname')->toArray();
        $amenityData = $amenityAccess->pluck('totalAccess')->toArray();
        $bookinglabels = $bookings->pluck('month')->toArray();
        $bookingData = $bookings->pluck('bookingTotal')->toArray();
        $roomlabels = $roomBookings->pluck('roomnum')->toArray();  
        $roombookData = $roomBookings->pluck('totalBookings')->toArray();
        $labels = $daybookings->map(fn($b) => \Carbon\Carbon::parse($b->week_start)->format('m-d-Y'))->toArray();
        $weekbookingData = $daybookings->pluck('bookingTotal')->toArray();
        $daytourData = $daytour->pluck('daytourTotal')->toArray();

        $feedbackNotification = FeedbackTable::where('status', 'Unread')->count();

        $notificationInquiry = ChatTable::where('status', 'Unread')
            ->distinct('guestID')
            ->count('guestID');

        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();
        $userLogIns = SessionLogTable::whereBetween('sessioncreated', [$todayStart, $todayEnd])->count();

        $availableRooms = RoomTable::where('status', 'Available')->count();
        $unavailableRooms = RoomTable::where('status', 'Unavailable')->count();
        $maintenancedRooms = RoomTable::where('status', 'Under Maintenance')->count();

        $availableCottages = CottageTable::where('status', 'Available')->count();
        $unavailableCottages = CottageTable::where('status', 'Unavailable')->count();
        $maintenancedCottages = CottageTable::where('status', 'Under Maintenance')->count();

        return view('manager.dashboard', compact(
            'feedbackNotification', 'notificationInquiry', 'userLogIns', 
            'availableRooms', 'unavailableRooms', 'maintenancedRooms',
            'availableCottages', 'unavailableCottages', 'maintenancedCottages',
            'revenueLabels', 'revenueValues',
            'amenityLabels', 'amenityData',
            'bookinglabels', 'bookingData',
            'roomlabels', 'roombookData',
            'labels', 'weekbookingData', 'daytourData'
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

}