<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\BillingTable;
use App\Models\PaymentTable;

class BillingController extends Controller
{
    public function billingList()
    {
        $payments = PaymentTable::query()
            ->leftJoin('guest', 'payment.guestID', '=', 'guest.guestID')
            ->leftJoin('billing', 'payment.billingID', '=', 'billing.billingID')
            ->leftJoin('booking', 'billing.bookingID', '=', 'booking.bookingID')
            ->leftJoin('amenities', 'booking.amenityID', '=', 'amenities.amenityID')
            ->leftJoin('menu_bookings', 'booking.bookingID', '=', 'menu_bookings.booking_id')
            ->select(
                'payment.paymentID',
                'payment.totaltender',
                DB::raw('CONCAT(guest.firstname, " ", guest.lastname) AS guestname'),
                'billing.totalamount',

                // Amenity breakdown
                DB::raw('
                    (booking.adultguest * amenities.adultprice + booking.childguest * amenities.childprice)
                    AS amenity_total
                '),

                // Menu orders breakdown
                DB::raw('
                    COALESCE(SUM(menu_bookings.price * menu_bookings.quantity), 0)
                    AS menu_total
                ')
            )
            ->groupBy(
                'payment.paymentID',
                'payment.totaltender',
                'guest.firstname',
                'guest.lastname',
                'billing.totalamount',
                'booking.adultguest',
                'booking.childguest',
                'amenities.adultprice',
                'amenities.childprice'
            )
            ->orderBy('payment.paymentID', 'desc')
            ->paginate(10);

        return view('receptionist.billing', compact('payments'));
    }


}