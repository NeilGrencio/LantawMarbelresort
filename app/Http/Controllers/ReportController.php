<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\BookingTable;

class ReportController extends Controller
{
    public function viewReport(){
        return view('manager/report');
    }

    public function bookingReport(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('booking')
            ->leftjoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftjoin('rooms', 'booking.roomID', '=', 'rooms.roomID')
            ->leftjoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->leftjoin('cottages', 'booking.cottageID', '=', 'cottages.cottageID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'rooms.roomnum as room',
                'amenities.amenityname',
                'cottages.cottagename'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $bookings = $query->get();

        $totalsQuery = DB::table('booking');

        if ($from && $to) {
            $totalsQuery->whereBetween('bookingcreated', [$from, $to]);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(),
            'total_hotel' => (clone $totalsQuery)->whereNotNull('roomID')->count(),
            'total_cottage' => (clone $totalsQuery)->whereNotNull('cottageID')->count(),
            'total_amenity' => (clone $totalsQuery)->whereNotNull('amenityID')->count(),
        ];

        return view('manager.booking_report', compact('bookings', 'totals'));
    }

    public function exportPDF(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('booking')
            ->leftjoin('guest', 'booking.guestID', '=', 'guest.guestID')
            ->leftjoin('rooms', 'booking.roomID', '=', 'rooms.roomID')
            ->leftjoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->leftjoin('cottages', 'booking.cottageID', '=', 'cottages.cottageID')
            ->select(
                'booking.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'rooms.roomnum as room',
                'amenities.amenityname',
                'cottages.cottagename'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $bookings = $query->get();

        $totalsQuery = DB::table('booking');
        if ($from && $to) {
            $totalsQuery->whereBetween('bookingcreated', [$from, $to]);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(),
            'total_hotel' => (clone $totalsQuery)->whereNotNull('roomID')->count(),
            'total_cottage' => (clone $totalsQuery)->whereNotNull('cottageID')->count(),
            'total_amenity' => (clone $totalsQuery)->whereNotNull('amenityID')->count(),
        ];

        $pdf = Pdf::loadView('manager.booking_pdf', compact('bookings', 'totals', 'from', 'to'))->setPaper('a4', 'portrait');
        return $pdf->download('booking_report.pdf');
    }

    public function checkReport(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

         $query = DB::table('checkincheckout')
            ->leftjoin('guest', 'checkincheckout.guestID', '=', 'guest.guestID')
            ->leftjoin('booking', 'checkincheckout.bookingID', '=', 'booking.bookingID')
            ->select(
                'checkincheckout.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'booking.bookingID',
            );

        if ($from && $to) {
            $query->whereBetween('checkincheckout.date', [$from, $to]);
        }

        $check = $query->get();

        $totalsQuery = DB::table('checkincheckout');

        if ($from && $to) {
            $totalsQuery->whereBetween('date', [$from, $to]);
        }

        $totals = (clone $totalsQuery)->count();

        return view('manager.check_report', compact('check', 'totals'));
    }

    public function exportCheckPDF(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

         $query = DB::table('checkincheckout')
            ->leftjoin('guest', 'checkincheckout.guestID', '=', 'guest.guestID')
            ->leftjoin('booking', 'checkincheckout.bookingID', '=', 'booking.bookingID')
            ->select(
                'checkincheckout.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'booking.bookingID',
            );

        if ($from && $to) {
            $query->whereBetween('checkincheckout.date', [$from, $to]);
        }

        $check = $query->get();

        $totalsQuery = DB::table('checkincheckout');

        if ($from && $to) {
            $totalsQuery->whereBetween('date', [$from, $to]);
        }

        $totals = (clone $totalsQuery)->count();

        $pdf = Pdf::loadView('manager.check_pdf', compact('check', 'totals', 'from', 'to'))->setPaper('a4', 'portrait');
        return $pdf->download('check_report.pdf');
    }
    public function revenueReport(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->join('guest', 'payment.guestID', '=', 'guest.guestID')
            ->leftJoin('booking', 'billing.bookingID', '=', 'booking.bookingID')
            ->leftJoin('orders', 'billing.orderID', '=', 'orders.orderID')
            ->leftJoin('amenities', 'billing.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select(
                'payment.*',
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
            $query->whereBetween('payment.datepayment', [$from, $to]);
        }

        $payments = $query->get();

        $totalsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID');

        $totals = [
            'all'     => (clone $totalsQuery)->count(),
            'booking' => (clone $totalsQuery)->whereNotNull('billing.bookingID')->count(),
            'order'   => (clone $totalsQuery)->whereNotNull('billing.orderID')->count(),
            'amenity' => (clone $totalsQuery)->whereNotNull('billing.amenityID')->count(),
        ];

         return view('manager.revenue_report', compact('payments', 'totals'));
    }
    public function exportRevenuePDF(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID')
            ->join('guest', 'payment.guestID', '=', 'guest.guestID')
            ->leftJoin('booking', 'billing.bookingID', '=', 'booking.bookingID')
            ->leftJoin('orders', 'billing.orderID', '=', 'orders.orderID')
            ->leftJoin('amenities', 'billing.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')
            ->leftJoin('discount', 'billing.discountID', '=', 'discount.discountID')
            ->select(
                'payment.*',
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
            $query->whereBetween('payment.datepayment', [$from, $to]);
        }

        $payments = $query->get();

        $totalsQuery = DB::table('payment')
            ->join('billing', 'payment.billingID', '=', 'billing.billingID');

        $totals = [
            'all'     => (clone $totalsQuery)->count(),
            'booking' => (clone $totalsQuery)->whereNotNull('billing.bookingID')->count(),
            'order'   => (clone $totalsQuery)->whereNotNull('billing.orderID')->count(),
            'amenity' => (clone $totalsQuery)->whereNotNull('billing.amenityID')->count(),
        ];

        $pdf = Pdf::loadView('manager.revenue_pdf', compact('payments', 'totals', 'from', 'to'))->setPaper('a4', 'portrait');
        return $pdf->download('revenue_report.pdf');
    }

    public function guestReport(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('guest')
            ->leftJoin('booking', 'guest.guestID', '=', 'booking.guestID')
            ->select(
                'guest.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'booking.bookingID',
                'booking.bookingcreated'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $guest = $query->get();

        $totalsQuery = DB::table('guest')
            ->leftJoin('booking', 'guest.guestID', '=', 'booking.guestID');

        if ($from && $to) {
            $totalsQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(),
            'total_Hguest' => (clone $totalsQuery)->where('role', 'Guest')->count(),
            'total_Dguest' => (clone $totalsQuery)->where('role', 'Day-Tour Guest')->count(),
        ];

        return view('manager.guest_report', compact('guest', 'totals'));
    }
    public function exportGuestPDF(Request $request){
        $from = $request->query('from');
        $to = $request->query('to');

        $query = DB::table('guest')
            ->leftJoin('booking', 'guest.guestID', '=', 'booking.guestID')
            ->select(
                'guest.*',
                DB::raw("CONCAT(guest.firstname, ' ', guest.lastname) as guestname"),
                'booking.bookingID',
                'booking.bookingcreated'
            );

        if ($from && $to) {
            $query->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $guest = $query->get();

        $totalsQuery = DB::table('guest')
            ->leftJoin('booking', 'guest.guestID', '=', 'booking.guestID');

        if ($from && $to) {
            $totalsQuery->whereBetween('booking.bookingcreated', [$from, $to]);
        }

        $totals = [
            'total_all' => (clone $totalsQuery)->count(),
            'total_Hguest' => (clone $totalsQuery)->where('role', 'Guest')->count(),
            'total_Dguest' => (clone $totalsQuery)->where('role', 'Day-Tour Guest')->count(),
        ];

        $pdf = Pdf::loadView('manager.guest_pdf', compact('guest', 'totals', 'from', 'to'))->setPaper('a4', 'portrait');
        return $pdf->download('guest_report.pdf');
    }
}
