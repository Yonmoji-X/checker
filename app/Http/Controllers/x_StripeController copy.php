<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function index()
    {
        // config からプラン情報を取得して view に渡す
        $plans = config('stripe.plans_list');
        return view('checkout.index', compact('plans'));
    }

    public function createSession(Request $request)
    {
        $plan = $request->input('plan');

        // 無料プランは Stripe をスキップ
        if ($plan === 'free') {
            $user = $request->user();
            $user->stripe_plan = config('stripe.plans.free');
            $user->stripe_status = 'active';
            $user->stripe_subscription_id = null;
            $user->stripe_customer_id = null;
            $user->save();

            return response()->json([
                'redirect' => route('checkout.free_success'),
            ]);
        }

        Stripe::setApiKey(config('stripe.secret'));

        $priceId = match ($plan) {
            'standard' => config('stripe.plans.standard'),
            'premium' => config('stripe.plans.premium', null),
            default => config('stripe.plans.free'),
        };

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $request->user()->email,
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('checkout')->with('error', 'セッション情報が見つかりません。');
        }

        Stripe::setApiKey(config('stripe.secret'));
        $session = Session::retrieve($sessionId);
        $user = $request->user();

        $lineItems = Session::allLineItems($sessionId, ['limit' => 1]);
        $planId = $lineItems->data[0]->price->id ?? null;

        $user->stripe_customer_id = $session->customer;
        $user->stripe_subscription_id = $session->subscription;
        $user->stripe_plan = $planId;
        $user->stripe_status = $session->payment_status;
        $user->save();

        return view('checkout.success');
    }

    public function freeSuccess()
    {
        return view('checkout.free_success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }

    public function myPlan()
    {
        $user = auth()->user();

        $planId = $user->stripe_plan;
        $status = $user->stripe_status;
        $subscriptionId = $user->stripe_subscription_id;
        $customerId = $user->stripe_customer_id;

        $planNames = [
            env('STRIPE_PLAN_FREE') => 'フリープラン',
            env('STRIPE_PLAN_STANDARD') => 'スタンダードプラン',
        ];

        $planName = $planNames[$planId] ?? '未購入';

        return view('checkout.plan', compact(
            'planName',
            'planId',
            'status',
            'subscriptionId',
            'customerId'
        ));
    }
}
