<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Subscription;

class StripeController extends Controller
{
    /**
     * プラン一覧ページ
     */
    public function index()
    {
        $plans = config('stripe.plans_list');
        return view('checkout.index', compact('plans'));
    }

    /**
     * Checkout セッション作成（初回購入用）
     */
    public function createSession(Request $request)
    {
        $planKey = $request->input('plan'); // free / light / standard / premium
        $plans = collect(config('stripe.plans_list'));
        $plan = $plans->firstWhere('key', $planKey);

        if (!$plan) {
            return response()->json(['error' => 'プランが見つかりません'], 400);
        }

        $user = $request->user();

        // -------------------------
        // 初回 free プラン選択時（Stripe に接続しない）
        // -------------------------
        if ($planKey === 'free' && !$user->stripe_customer_id) {
            $user->stripe_plan = $plan['stripe_plan'];
            $user->stripe_status = 'active';
            $user->stripe_subscription_id = null;
            $user->stripe_customer_id = null;
            $user->save();

            return response()->json([
                'redirect' => route('checkout.free_success'),
            ]);
        }

        // -------------------------
        // 既存顧客や有料プランの場合は updateSubscription に委譲
        // -------------------------
        return $this->updateSubscription($request);
    }

    /**
     * サブスクリプション更新用（既存 Stripe 顧客用）
     */
    public function updateSubscription(Request $request)
    {
        $planKey = $request->input('plan'); // free / light / standard / premium
        $plans = collect(config('stripe.plans_list'));
        $plan = $plans->firstWhere('key', $planKey);

        if (!$plan) {
            return response()->json(['error' => 'プランが見つかりません'], 400);
        }

        $user = $request->user();
        Stripe::setApiKey(config('stripe.secret'));

        // -------------------------
        // free プランも含め Stripe 上でサブスク更新
        // -------------------------
        if ($user->stripe_customer_id && $user->stripe_subscription_id) {
            try {
                $subscription = Subscription::update(
                    $user->stripe_subscription_id,
                    [
                        'items' => [
                            ['price' => $plan['stripe_plan']],
                        ],
                        'proration_behavior' => 'create_prorations', // 日割り請求対応
                    ]
                );

                $user->stripe_plan = $plan['stripe_plan'];
                $user->stripe_status = $subscription->status;
                $user->save();

                // free プランの場合は専用ページへ
                $redirectRoute = $planKey === 'free' ? 'checkout.free_success' : 'checkout.success';

                return response()->json(['redirect' => route($redirectRoute)]);
            } catch (\Exception $e) {
                // 更新失敗時は Checkout Session に誘導
                return $this->createCheckoutSession($user, $plan);
            }
        }

        // -------------------------
        // 初回有料プラン購入（Stripe 上に顧客なし）
        // -------------------------
        return $this->createCheckoutSession($user, $plan);
    }

    /**
     * Stripe Checkout セッション作成（初回有料プラン購入用）
     */
    private function createCheckoutSession($user, $plan)
    {
        Stripe::setApiKey(config('stripe.secret'));
        $session = Session::create([
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

    /**
     * Checkout 成功時
     */
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

    /**
     * 無料プラン成功ページ
     */
    public function freeSuccess()
    {
        return view('checkout.free_success');
    }

    /**
     * キャンセルページ
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }

    /**
     * ユーザーの現在のプラン情報
     */
    public function myPlan()
    {
        $user = auth()->user();
        $plans = collect(config('stripe.plans_list'));
        $plan = $plans->firstWhere('stripe_plan', $user->stripe_plan);
        $planName = $plan['name'] ?? '未購入';

        return view('checkout.plan', [
            'planName' => $planName,
            'planId' => $user->stripe_plan,
            'status' => $user->stripe_status,
            'subscriptionId' => $user->stripe_subscription_id,
            'customerId' => $user->stripe_customer_id,
        ]);
    }
}
