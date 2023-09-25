<?php

namespace App\Services;

use Stripe;

class BaseService
{
    public function successResponse($data, $statusCode, $message)
    {
        return response()->json([
            'status' => true,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public function errorResponse($data, $statusCode, $message)
    {
        return response()->json([
            'status' => false,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

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
