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

            // --- ROOM BOOKINGS ---
            ->leftJoin('roombook', 'booking.bookingID', '=', 'roombook.bookingID')
            ->leftJoin('rooms', 'roombook.roomID', '=', 'rooms.roomID')

            // --- COTTAGE BOOKINGS ---
            ->leftJoin('cottagebook', 'booking.bookingID', '=', 'cottagebook.bookingID')
            ->leftJoin('cottages', 'cottagebook.cottageID', '=', 'cottages.cottageID')

            // --- MENU BOOKINGS ---
            ->leftJoin('menu_bookings', 'booking.bookingID', '=', 'menu_bookings.booking_id')

            // --- ADDITIONAL CHARGES ---
            ->leftJoin('additionalcharges', 'billing.chargeID', '=', 'additionalcharges.chargeID')

            ->select(
                'payment.paymentID',
                'payment.totaltender',
                DB::raw('CONCAT(guest.firstname, " ", guest.lastname) AS guestname'),
                'billing.totalamount',

                // --- Amenity breakdown ---
                DB::raw('
                    (booking.adultguest * amenities.adultprice + booking.childguest * amenities.childprice)
                    AS amenity_total
                '),

                // --- Menu breakdown ---
                DB::raw('
                    COALESCE(SUM(menu_bookings.price * menu_bookings.quantity), 0)
                    AS menu_total
                '),

                // --- Room breakdown ---
                DB::raw('
                    COALESCE(SUM(rooms.price), 0)
                    AS room_total
                '),

                // --- Cottage breakdown ---
                DB::raw('
                    COALESCE(SUM(cottages.price), 0)
                    AS cottage_total
                '),

                // --- Additional Charges breakdown ---
                DB::raw('
                    COALESCE(SUM(additionalcharges.amount), 0)
                    AS additional_total
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