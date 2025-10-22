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
        $planKey = $request->input('plan'); // free / light / standard / premium
        $plans = collect(config('stripe.plans_list'));

        // プラン情報を取得
        $plan = $plans->firstWhere('key', $planKey);

        if (!$plan) {
            return response()->json(['error' => 'プランが見つかりません'], 400);
        }

        $user = $request->user();

        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        // -------------------------
        // 無料プランを選択した場合
        // -------------------------
        if ($planKey === 'free') {

            // すでに Stripe にサブスクリプションがある場合は free プランに更新
            if ($user->stripe_subscription_id) {
                \Stripe\Subscription::update($user->stripe_subscription_id, [
                    'items' => [
                        ['price' => $plan['stripe_plan']], // Stripe 上の free プラン price ID
                    ],
                ]);
            }

            // ローカル DB 更新
            $user->stripe_plan = $plan['stripe_plan'];
            $user->stripe_status = 'active';
            $user->save();

            return response()->json([
                'redirect' => route('checkout.free_success'),
            ]);
        }

        // -------------------------
        // 有料プランを選択した場合
        // -------------------------
        // すでに Stripe に顧客とサブスクリプションがある場合は更新
        if ($user->stripe_customer_id && $user->stripe_subscription_id) {
            $subscription = \Stripe\Subscription::update($user->stripe_subscription_id, [
                'items' => [
                    ['price' => $plan['stripe_plan']],
                ],
            ]);

            // ローカル DB 更新
            $user->stripe_plan = $plan['stripe_plan'];
            $user->stripe_status = $subscription->status;
            $user->save();

            return response()->json([
                'redirect' => route('checkout.success'),
            ]);
        }

        // -------------------------
        // 初回有料プラン購入用
        // -------------------------
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan['stripe_plan'],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $user->email,
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

        $plans = collect(config('stripe.plans_list'));
        $plan = $plans->firstWhere('stripe_plan', $planId);
        $planName = $plan['name'] ?? '未購入';

        return view('checkout.plan', compact(
            'planName',
            'planId',
            'status',
            'subscriptionId',
            'customerId'
        ));
    }
}
