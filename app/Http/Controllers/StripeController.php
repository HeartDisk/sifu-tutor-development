<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createCheckoutSession()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Sifututor Payment',
                        ],
                        'unit_amount' => 2000,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url, 303);
    }

    public function checkoutSuccess()
    {
        // Handle the successful payment here
        return view('checkout.success');
    }

    public function checkoutCancel()
    {
        // Handle the canceled payment here
        return view('checkout.cancel');
    }
}
