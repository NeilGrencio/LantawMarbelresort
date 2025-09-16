<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

class PaymentController extends Controller
{
    public function createGcashPayment(Request $request)
    {
        $amount = $request->input('amount') * 100; // PayMongo expects centavos

        $paymentIntent = Paymongo::paymentIntent()->create([
            'amount' => $amount,
            'payment_method_allowed' => ['gcash'],
            'currency' => 'PHP',
        ]);

        $paymentMethod = Paymongo::paymentMethod()->create([
            'type' => 'gcash',
            'details' => [
                'email' => $request->input('email'),
            ],
        ]);

        $attach = Paymongo::paymentIntent()->attach(
            $paymentIntent->id,
            $paymentMethod->id
        );

        return response()->json([
            'checkout_url' => $attach->next_action->redirect->url
        ]);
    }
}
