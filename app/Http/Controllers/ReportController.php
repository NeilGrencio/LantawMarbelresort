<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\BookingTable;
use App\Models\RoomTable;
use App\Models\CottageTable;
use App\Models\AmenityTable;
use Carbon\Carbon;
use App\Models\SessionLogTable;

class ReportController extends Controller
{
    public function viewReport(){
        $today = Carbon::now()->format('M. d, Y');
        return view('manager/report', compact('today'));
    }

    public function bookingReport(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $rooms = $request->query('room');
        $cottages = $request->query('cottage');
        $amenities = $request->query('amenity');

        $query = DB::table('booking')
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->select(
                'booking.bookingID',
                'booking.guestID',
                'booking.guestamount',
                'booking.totalprice',
                'booking.bookingstart',
                'booking.bookingend',
                'rooms.roomnum as room',
                'amenities.amenityname',
                'cottages.cottagename'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        if ($rooms) {
            $query->where('rooms.roomID', $rooms);
        }

        if ($cottages) {
            $query->where('cottages.cottageID', $cottages);
        }

        if ($amenities) {
            $query->where('amenities.amenityID', $amenities);
        }

        $bookings = $query->get();

        // Totals query with same filters
        $totalsQuery = DB::table('booking')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID');

        if ($from && $to) {
            $totalsQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        if ($rooms) {
            $totalsQuery->where('rooms.roomID', $rooms);
        }

        if ($cottages) {
            $totalsQuery->where('cottages.cottageID', $cottages);
        }

        if ($amenities) {
            $totalsQuery->where('amenities.amenityID', $amenities);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(DB::raw('DISTINCT booking.bookingID')),
            'total_hotel' => (clone $totalsQuery)->whereNotNull('roombook.roomID')->count(DB::raw('DISTINCT booking.bookingID')),
            'total_cottage' => (clone $totalsQuery)->whereNotNull('cottagebook.cottageID')->count(DB::raw('DISTINCT booking.bookingID')),
            'total_amenity' => (clone $totalsQuery)->whereNotNull('amenities.amenityID')->count(DB::raw('DISTINCT booking.bookingID')),
        ];

        $roomSelect = RoomTable::all();
        $cottageSelect = CottageTable::all();
        $amenitySelect = AmenityTable::all();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed a Genereted Report: Booking Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        return view('manager.booking_report', compact(
            'bookings', 'totals', 'roomSelect', 'cottageSelect', 'amenitySelect'
        ));
    }
    public function exportPDF(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
        $rooms = $request->query('room');
        $cottages = $request->query('cottage');
        $amenities = $request->query('amenity');

        $query = DB::table('booking')
            ->leftJoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->select(
                'booking.bookingID',
                'booking.guestID',
                'booking.guestamount',
                'booking.totalprice',
                'booking.bookingstart',
                'booking.bookingend',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'rooms.roomnum as room',
                'amenities.amenityname',
                'cottages.cottagename'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        if ($rooms) {
            $query->where('rooms.roomID', $rooms);
        }

        if ($cottages) {
            $query->where('cottages.cottageID', $cottages);
        }

        if ($amenities) {
            $query->where('amenities.amenityID', $amenities);
        }

        $bookings = $query->get();

        $totalsQuery = DB::table('booking')
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID');

        if ($from && $to) {
            $totalsQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        if ($rooms) {
            $totalsQuery->where('rooms.roomID', $rooms);
        }

        if ($cottages) {
            $totalsQuery->where('cottages.cottageID', $cottages);
        }

        if ($amenities) {
            $totalsQuery->where('amenities.amenityID', $amenities);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(DB::raw('DISTINCT booking.bookingID')),
            'total_hotel' => (clone $totalsQuery)->whereNotNull('roombook.roomID')->count(DB::raw('DISTINCT booking.bookingID')),
            'total_cottage' => (clone $totalsQuery)->whereNotNull('cottagebook.cottageID')->count(DB::raw('DISTINCT booking.bookingID')),
            'total_amenity' => (clone $totalsQuery)->whereNotNull('amenities.amenityID')->count(DB::raw('DISTINCT booking.bookingID')),
        ];

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Printed a Genereted Report: Booking Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'manager.booking_pdf',
            compact('bookings', 'totals', 'from', 'to')
        )->setPaper('a4', 'portrait');

        return $pdf->download('booking_report.pdf');
    }


    public function revenueReport(Request $request)
    {
        $from     = $request->query('from');
        $to       = $request->query('to');
        $room     = $request->query('room');
        $cottage  = $request->query('cottage');
        $amenity  = $request->query('amenity');

        $paymentsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->join('guest', 'payment.guestID', '=', 'guest.guestID')
            ->leftJoin('booking', 'billing.bookingID', '=', 'booking.bookingID')
            ->leftJoin('menu_bookings', 'billing.orderID', '=', 'menu_bookings.id')
            ->leftJoin('amenities', 'billing.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select(
                'payment.*',
                'billing.bookingID',
                'billing.orderID', 
                'billing.amenityID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                DB::raw("
                    CASE
                        WHEN billing.bookingID IS NOT NULL THEN 'Booking'
                        WHEN billing.orderID IS NOT NULL THEN 'Menu Order'
                        WHEN billing.amenityID IS NOT NULL THEN 'Amenity'
                        ELSE 'Unknown'
                    END as payment_type
                "),
                'billing.totalamount',
                'additionalcharges.amount as extracharge',
                'discount.amount as discount',
                DB::raw("
                    ROUND(
                        (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                        (1 - (IFNULL(discount.amount, 0) / 100)),
                    2) as total
                ")
            );

        if ($from && $to) {
            $paymentsQuery->whereBetween('payment.datepayment', [$from, $to]);
        }
        if ($room) {
            $paymentsQuery->where('booking.roomID', $room);
        }
        if ($cottage) {
            $paymentsQuery->where('booking.cottageID', $cottage);
        }
        if ($amenity) {
            $paymentsQuery->where('billing.amenityID', $amenity);
        }

        $payments = $paymentsQuery->get();

        $totalsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID');

        if ($from && $to) {
            $totalsQuery->whereBetween('payment.datepayment', [$from, $to]);
        }
        if ($room) {
            $totalsQuery->join('booking', 'billing.bookingID', '=', 'booking.bookingID')
                        ->where('booking.roomID', $room);
        }
        if ($cottage) {
            $totalsQuery->join('booking as cb', 'billing.bookingID', '=', 'cb.bookingID')
                        ->where('cb.cottageID', $cottage);
        }
        if ($amenity) {
            $totalsQuery->where('billing.amenityID', $amenity);
        }

        $totals = [
            'all'     => number_format((clone $totalsQuery)
                            ->selectRaw("SUM((billing.totalamount + IFNULL(additionalcharges.amount,0)) * (1 - IFNULL(discount.amount,0)/100)) as total")
                            ->value('total') ?? 0, 2),

            'booking' => number_format((clone $totalsQuery)
                            ->whereNotNull('billing.bookingID')
                            ->selectRaw("SUM((billing.totalamount + IFNULL(additionalcharges.amount,0)) * (1 - IFNULL(discount.amount,0)/100)) as total")
                            ->value('total') ?? 0, 2),

            'order'   => number_format((clone $totalsQuery)
                            ->whereNotNull('billing.orderID') 
                            ->selectRaw("SUM((billing.totalamount + IFNULL(additionalcharges.amount,0)) * (1 - IFNULL(discount.amount,0)/100)) as total")
                            ->value('total') ?? 0, 2),

            'amenity' => number_format((clone $totalsQuery)
                            ->whereNotNull('billing.amenityID')
                            ->selectRaw("SUM((billing.totalamount + IFNULL(additionalcharges.amount,0)) * (1 - IFNULL(discount.amount,0)/100)) as total")
                            ->value('total') ?? 0, 2),
        ];

        $roomSelect = DB::table('rooms')->get();
        $cottageSelect = DB::table('cottages')->get();
        $amenitySelect = DB::table('amenities')->get();

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed a Genereted Report: Revenue Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        return view('manager.revenue_report', compact('payments', 'totals', 'roomSelect', 'cottageSelect', 'amenitySelect'));
    }
    public function exportRevenuePDF(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        if ($from === 'null' || !$from) {
            $from = null;
        }
        if ($to === 'null' || !$to) {
            $to = null;
        }

        $paymentsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->join('guest', 'billing.guestID', '=', 'guest.guestID')
            ->leftJoin('booking', 'billing.bookingID', '=', 'booking.bookingID')
            ->leftJoin('orders', 'billing.orderID', '=', 'orders.orderID')
            ->leftJoin('amenities', 'billing.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select(
                'payment.*',
                'billing.bookingID',
                'billing.orderID',
                'billing.amenityID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                DB::raw("
                    CASE
                        WHEN billing.bookingID IS NOT NULL THEN 'Booking'
                        WHEN billing.orderID IS NOT NULL THEN 'Order'
                        WHEN billing.amenityID IS NOT NULL THEN 'Amenity'
                        ELSE 'Unknown'
                    END as payment_type
                "),
                'billing.totalamount',
                'additionalcharges.amount as extracharge',
                'discount.amount as discount',
                DB::raw("
                    ROUND(
                        (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                        (1 - (IFNULL(discount.amount, 0) / 100)),
                    2) as total
                ")
            );

        if ($from && $to) {
            $paymentsQuery->whereBetween('payment.datepayment', [$from, $to]);
        }

        $payments = $paymentsQuery->get();
        $totalsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->selectRaw("
                SUM(
                    (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                    (1 - (IFNULL(discount.amount, 0) / 100))
                ) as overall,
                SUM(
                    CASE WHEN billing.bookingID IS NOT NULL 
                        THEN (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                            (1 - (IFNULL(discount.amount, 0) / 100)) ELSE 0 END
                ) as booking,
                SUM(
                    CASE WHEN billing.orderID IS NOT NULL 
                        THEN (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                            (1 - (IFNULL(discount.amount, 0) / 100)) ELSE 0 END
                ) as orders,
                SUM(
                    CASE WHEN billing.amenityID IS NOT NULL 
                        THEN (billing.totalamount + IFNULL(additionalcharges.amount, 0)) * 
                            (1 - (IFNULL(discount.amount, 0) / 100)) ELSE 0 END
                ) as amenities
            ");

        if ($from && $to) {
            $totalsQuery->whereBetween('payment.datepayment', [$from, $to]);
        }

        $totals = $totalsQuery->first();

        // Group payments by type
        $grouped = [
            'booking' => $payments->whereNotNull('bookingID'),
            'order'   => $payments->whereNotNull('orderID'),
            'amenity' => $payments->whereNotNull('amenityID'),
        ];

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Printed a Genereted Report: Revenue Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        $pdf = Pdf::loadView('manager.revenue_pdf', compact('payments', 'totals', 'from', 'to', 'grouped'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('revenue_report.pdf');
    }

    public function guestReport(Request $request)
    {
        $from   = $request->query('from');
        $to     = $request->query('to');
        $filter = $request->query('filter');

        $from = ($from === 'null' || !$from) ? null : $from;
        $to   = ($to === 'null' || !$to) ? null : $to;

        $bookingQuery = DB::table('guest')
            ->join('booking', 'guest.guestID', '=', 'booking.guestID')
            ->select(
                'guest.guestID',
                'guest.firstname',
                'guest.lastname',
                'guest.role',
                DB::raw('MIN(booking.bookingcreated) as registered_at'),
                DB::raw('COUNT(booking.bookingID) - 1 as guest_return_count')
            )
            ->groupBy('guest.guestID', 'guest.firstname', 'guest.lastname', 'guest.role');

        if ($from && $to) {
            $bookingQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $qrQuery = DB::table('guest')
            ->join('qrcodes', 'guest.guestID', '=', 'qrcodes.guestID')
            ->select(
                'guest.guestID',
                'guest.firstname',
                'guest.lastname',
                'guest.role',
                DB::raw('MIN(qrcodes.accessdate) as registered_at'),
                DB::raw('COUNT(qrcodes.qrID) - 1 as guest_return_count')
            )
            ->groupBy('guest.guestID', 'guest.firstname', 'guest.lastname', 'guest.role');

        if ($from && $to) {
            $qrQuery->whereBetween('qrcodes.accessdate', [$from, $to]);
        }

        if ($filter === 'hotel') {
            $combined = $bookingQuery;
        } elseif ($filter === 'daytour') {
            $combined = $qrQuery;
        } else {
            $combined = $bookingQuery->unionAll($qrQuery);
        }

        $guests = DB::query()->fromSub($combined, 'combined')->get();

        $total_all = $guests->unique('guestID')->count();
        $total_Hguest = $guests->filter(function ($g) {
            $role = $g->role ?? '';
            return stripos($role, 'day') === false;
        })->unique('guestID')->count();
        $total_Dguest = $guests->filter(function ($g) {
            $role = $g->role ?? '';
            return stripos($role, 'day') !== false;
        })->unique('guestID')->count();

        $totals = [
            'total_all'    => $total_all,
            'total_Hguest' => $total_Hguest,
            'total_Dguest' => $total_Dguest,
        ];

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed a Genereted Report: Guest Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        return view('manager.guest_report', [
            'guest'  => $guests,
            'totals' => $totals,
            'from'   => $from,
            'to'     => $to,
            'filter' => $filter,
        ]);
    }
   
    public function exportGuestPDF(Request $request)
    {
        $from = $request->query('from');
        $to   = $request->query('to');

        if ($from === 'null' || !$from) {
            $from = null;
        }
        if ($to === 'null' || !$to) {
            $to = null;
        }

        $bookingQuery = DB::table('guest')
            ->join('booking', 'guest.guestID', '=', 'booking.guestID')
            ->select(
                'guest.guestID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                DB::raw("'Hotel Guest' as role"),
                DB::raw('MIN(booking.bookingcreated) as registered_at'),
                DB::raw('COUNT(booking.bookingID) - 1 as guest_return_count')
            )
            ->groupBy('guest.guestID', 'guest.firstname', 'guest.lastname');

        if ($from && $to) {
            $bookingQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $qrQuery = DB::table('guest')
            ->join('qrcodes', 'guest.guestID', '=', 'qrcodes.guestID')
            ->select(
                'guest.guestID',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                DB::raw("'Day-Tour Guest' as role"),
                DB::raw('MIN(qrcodes.accessdate) as registered_at'),
                DB::raw('COUNT(qrcodes.qrID) - 1 as guest_return_count')
            )
            ->groupBy('guest.guestID', 'guest.firstname', 'guest.lastname');

        if ($from && $to) {
            $qrQuery->whereBetween('qrcodes.accessdate', [$from, $to]);
        }

        $guest = $bookingQuery->unionAll($qrQuery)->get();

        $totals = [
            'total_all'    => $guest->count(),
            'total_Hguest' => $guest->where('role', 'Hotel Guest')->count(),
            'total_Dguest' => $guest->where('role', 'Day-Tour Guest')->count(),
        ];

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Print a Genereted Report: Guest Report '. ($from && $to ? "from $from to $to" : 'All Time'),
                'date'     => now(),
            ]);
        }

        $pdf = Pdf::loadView('manager.guest_pdf', compact('guest', 'totals', 'from', 'to'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('guest_report.pdf');
    }
}
