<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;

class PaymentController extends Controller
{
    public function postPayment($orderTotal)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );

            Stripe\Stripe::setApiKey(
                env('STRIPE_SECRET')
            );

            $response = $stripe->paymentIntents->create([
                'amount' => $orderTotal,
                'currency' => 'usd',
                'payment_method' => 'pm_card_visa',
            ]);

            return $response->id;

        } catch (\Throwable $th) {

            return 500;
        }
    }
}