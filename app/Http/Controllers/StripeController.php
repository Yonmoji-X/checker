<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentMethod;

class StripeController extends Controller
{
    /**
     * プラン一覧ページ
     */
    public function index()
    {
        $plans = config('stripe.plans_list');
        $user = auth()->user();
        return view('checkout.index', compact('plans', 'user'));
    }

    /**
     * Checkout セッション作成または無料プラン登録
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
        Stripe::setApiKey(config('stripe.secret'));

        // -------------------------
        // 1️⃣ 無料プランの場合
        // -------------------------
        if ($planKey === 'free') {
            // Stripe顧客が未作成なら作成（カード不要）
            if (!$user->stripe_customer_id) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name'  => $user->name,
                    'metadata' => [
                        'app_user_id' => $user->id,
                    ],
                ]);
                $user->stripe_customer_id = $customer->id;
            }

            // Stripe上でも「無料プランのサブスク」を作成
            $subscription = Subscription::create([
                'customer' => $user->stripe_customer_id,
                'items' => [
                    ['price' => $plan['stripe_plan']],
                ],
                'trial_period_days' => 0,
                'payment_behavior' => 'default_incomplete', // 無料なので課金なし
            ]);

            // DB更新
            $user->stripe_plan = $plan['stripe_plan'];
            $user->stripe_status = $subscription->status;
            $user->stripe_subscription_id = $subscription->id;
            $user->save();

            return response()->json([
                'redirect' => route('checkout.free_success'),
            ]);
        }

        // -------------------------
        // 2️⃣ 有料プランの場合
        // -------------------------
        $trialDays = 0; // デフォルト値

        // usersテーブルのuse_trialカラムを確認してtrueならトライアル日数をstripe.phpから読み込み適用
        if ($user->use_trial === false && ($plan['trial_days'] ?? 0) > 0) {
            $trialDays = $plan['trial_days'];  // 初回のみstripe.phpのトライアル日数を適用
            $user->use_trial = true;  // トライアル使用済みに更新
            $user->save();
        }
        

        if ($user->stripe_customer_id) {
            $hasPaymentMethod = $this->customerHasPaymentMethod($user->stripe_customer_id);

            if ($user->stripe_subscription_id && $hasPaymentMethod) {
                //  現在のサブスク情報を取得（updateに必要な item ID を取る）
                $subscription = Subscription::retrieve($user->stripe_subscription_id); 
                $subscriptionItemId = $subscription->items->data[0]->id ?? null; 

                if ($subscriptionItemId) {
                    //  正しい形式でサブスク更新（idを指定してprice変更）
                    $updated = Subscription::update(
                        $user->stripe_subscription_id,
                        [
                            'items' => [
                                [
                                    'id' => $subscriptionItemId, 
                                    'price' => $plan['stripe_plan'],
                                ],
                            ],
                            'proration_behavior' => 'create_prorations', //  課金差額の調整あり
                        ]
                    );

                    //  DB更新
                    $user->stripe_plan = $plan['stripe_plan'];
                    $user->stripe_status = $updated->status;
                    $user->save();

                    return response()->json([
                        'redirect' => route('checkout.success'),
                    ]);
                } else {
                    //  item が見つからない場合は Checkout セッションを新規作成
                    return $this->createCheckoutSession($user, $plan, $trialDays);
                }
            } else {
                //  支払い情報がない場合は Checkout へ誘導
                return $this->createCheckoutSession($user, $plan, $trialDays);
            }
        }

        // Stripe未登録顧客
        return $this->createCheckoutSession($user, $plan, $trialDays);
    }

    /**
     * Checkout セッション作成
     */
    private function createCheckoutSession($user, $plan, $trialDays = 0)
    {
        $params = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $plan['stripe_plan'],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $user->email,
        ];

        // ⭐ トライアル期間がある場合は trial_period_days を追加
        if ($trialDays > 0) {
            $params['subscription_data'] = [
                'trial_period_days' => $trialDays,
            ];
        }

        $session = Session::create($params);
        return response()->json(['id' => $session->id]);
    }
    // {
    //     $session = Session::create([
    //         'payment_method_types' => ['card'],
    //         'line_items' => [[
    //             'price' => $plan['stripe_plan'],
    //             'quantity' => 1,
    //         ]],
    //         'mode' => 'subscription',
    //         'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
    //         'cancel_url' => route('checkout.cancel'),
    //         'customer_email' => $user->email,
    //     ]);

    //     return response()->json(['id' => $session->id]);
    // }

    /**
     * Checkout 成功時----------------------------
     */
    // public function success(Request $request)
    // {
    //     $sessionId = $request->get('session_id');
    //     if (!$sessionId) {
    //         return redirect()->route('checkout')->with('error', 'セッション情報が見つかりません。');
    //     }
        
    //     Stripe::setApiKey(config('stripe.secret'));
        
    //     if ($sessionId) {
    //         $user = $request->user();
    //         $session = Session::retrieve($sessionId);
    
    //         $lineItems = Session::allLineItems($sessionId, ['limit' => 1]);
    //         $planId = $lineItems->data[0]->price->id ?? null;
    
    //         $user->stripe_customer_id = $session->customer;
    //         $user->stripe_subscription_id = $session->subscription;
    //         $user->stripe_plan = $planId;
    //         $user->stripe_status = $session->payment_status;
    //         $user->save();
    //     }

    //     return view('checkout.success');
    // }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $user = $request->user();

        Stripe::setApiKey(config('stripe.secret'));

        // ✅ session_id がある場合だけ Stripe から取得
        if ($sessionId) {
            $session = Session::retrieve($sessionId);
            $lineItems = Session::allLineItems($sessionId, ['limit' => 1]);
            $planId = $lineItems->data[0]->price->id ?? null;

            $user->stripe_customer_id = $session->customer;
            $user->stripe_subscription_id = $session->subscription;
            $user->stripe_plan = $planId;
            $user->stripe_status = $session->payment_status;
            $user->save();
        }

        // ✅ session_id がない場合でもビューをそのまま表示
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
     * 現在のプラン情報
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

    /**
     * 顧客に支払い方法が登録されているか確認
     */
    private function customerHasPaymentMethod($customerId)
    {
        $paymentMethods = PaymentMethod::all([
            'customer' => $customerId,
            'type' => 'card',
        ]);
        return count($paymentMethods->data) > 0;
    }
}
