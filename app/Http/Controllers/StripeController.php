<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }

    public function createSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $priceId = $request->input('price_id'); // JSから送信

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId, // 動的に指定
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }


    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        // ここで接し温情報を取得してユーザーと紐づける
        // Stripe\Checkout\Session::retrieve($sessionId);

        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
