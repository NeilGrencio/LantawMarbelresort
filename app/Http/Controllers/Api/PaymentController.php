<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Paymongo\Client; // ✅ this is the right import

class PaymentController extends Controller
{
    public function createGcashPayment(Request $request)
    {
        // $amount = $request->input('amount') * 100; // PayMongo uses centavos

        // // ✅ use Client, not Paymongo
        // $paymongo = new Client(env('PAYMONGO_SECRET_KEY'));

        // $paymentIntent = $paymongo->paymentIntents()->create([
        //     'amount' => $amount,
        //     'payment_method_allowed' => ['gcash'],
        //     'currency' => 'PHP',
        // ]);

        // $paymentMethod = $paymongo->paymentMethods()->create([
        //     'type' => 'gcash',
        //     'details' => [
        //         'email' => $request->input('email'),
        //     ],
        // ]);

        // $attach = $paymongo->paymentIntents()->attach(
        //     $paymentIntent['data']['id'],
        //     $paymentMethod['data']['id']
        // );

        // return response()->json([
        //     'checkout_url' => $attach['data']['attributes']['next_action']['redirect']['url']
        // ]);
    }

    public function handleWebhook(Request $request)
    {
        // $event = $request->all();

        // if (isset($event['data']['attributes']['status']) &&
        //     $event['data']['attributes']['status'] === 'succeeded') {
        //     // ✅ Mark booking as booked in DB
        // }

        // return response()->json(['ok' => true]);
    }
}
