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
                ->leftjoin('guest', 'payment.guestID', '=', 'guest.guestID')
                ->leftjoin('billing', 'payment.billingID', '=', 'billing.billingID')
                ->select('payment.totaltender',
                        DB::raw('CONCAT(guest.firstname, " ", guest.lastname) AS guestname'),
                        'billing.totalamount',)
                ->get();

                dd(session('username'));
        return view('receptionist.billing', compact('payments'));
    }
}