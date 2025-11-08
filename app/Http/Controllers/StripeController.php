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
    // public function index()
    // {
    //     $plans = config('stripe.plans_list');
    //     $user = auth()->user();
    //     return view('checkout.index', compact('plans', 'user'));
    // }
    // StripeController.php - index()内
    public function index()
    {
        $plans = config('stripe.plans_list');
        $user = auth()->user();
        $isCanceled = false;

        if($user && $user->stripe_subscription_id){
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
            try {
                $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
                $isCanceled = $subscription->cancel_at ? true : false;
            } catch (\Exception $e) {
                $isCanceled = false;
            }
        }

        return view('checkout.index', compact('plans', 'user', 'isCanceled'));
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

        // ⭐ 解約予定中なら取り消し
        if($user->stripe_subscription_id && $user->stripe_canceled_at) {
            try {
                \Stripe\Stripe::setApiKey(config('stripe.secret'));
                $subscription = \Stripe\Subscription::update(
                    $user->stripe_subscription_id,
                    ['cancel_at_period_end' => false]
                );
                $user->stripe_status = $subscription->status;
                $user->stripe_canceled_at = null;
                $user->save();
            } catch(\Exception $e) {
                return response()->json(['error' => '解約取り消しに失敗しました: '.$e->getMessage()], 500);
            }
        }


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
                
                if ($subscription->status === 'canceled' || $subscription->status === 'incomplete_expired') {
                    // DBリセット
                    $user->stripe_customer_id = null;
                    $user->stripe_subscription_id = null;
                    $user->stripe_plan = null;
                    $user->stripe_status = null;
                    $user->stripe_canceled_at = null;
                    $user->save();
                    // 既にキャンセル済 → 新しいセッションを作成
                    return $this->createCheckoutSession($user, $plan, $trialDays);
                }
                
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

        // ⭐ Stripeから最新のキャンセル日を取得して上書き（手動更新）
        if ($user->stripe_subscription_id) {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
            try {
                $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
                if ($subscription->cancel_at) {
                    $user->stripe_canceled_at = date('Y-m-d H:i:s', $subscription->cancel_at);
                    $user->save(); // ⭐ ローカルDBにも反映
                }
            } catch (\Exception $e) {
                // 通信エラー時などはスルー
            }
        }

        return view('checkout.plan', [
            'planName' => $planName,
            'planId' => $user->stripe_plan,
            'status' => $user->stripe_status,
            'subscriptionId' => $user->stripe_subscription_id,
            'customerId' => $user->stripe_customer_id,
            'cancelDate' => $user->stripe_canceled_at, // ⭐ Bladeで使う変数を渡す
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




    // =========解約関連処==========
    // 解約ページ表示
    public function unsubscribePage()
    {
        $user = auth()->user();
        $plans = collect(config('stripe.plans_list'));
        $plan = $plans->firstWhere('stripe_plan', $user->stripe_plan);
        $planName = $plan['name'] ?? '未購入';

        return view('checkout.unsubscribe', [
            'subscriptionId' => $user->stripe_subscription_id,
            'planName' => $planName,
        ]);
    }


      // ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
// ========================
// 解約処理（即時解約ではないパターン）
// ========================
    public function unsubscribe(Request $request)
    {
        $user = $request->user();

        if (!$user->stripe_subscription_id) {
            return redirect()->route('checkout.plan')
                ->with('error', 'サブスクリプションが存在しません。');
        }

        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        try {
            // Stripeのサブスクリプションをキャンセル（請求期間終了時に停止）
            $subscription = \Stripe\Subscription::update(
                $user->stripe_subscription_id,
                ['cancel_at_period_end' => true]
            );

            // ⭐ Stripe APIからキャンセル予定日時を取得
            $cancelAt = $subscription->cancel_at 
                ? date('Y-m-d H:i:s', $subscription->cancel_at)
                : null;

            // ⭐ DBを更新（stripe_canceled_at に保存）
            $user->stripe_status = $subscription->status;
            $user->stripe_canceled_at = $cancelAt;
            $user->save();

            // ⭐ 成功メッセージに解約日を含める
            return redirect()->route('checkout.plan')
                ->with('success', $cancelAt 
                    ? "サブスクリプションを解約しました。{$cancelAt} に解約予定です。" 
                    : "サブスクリプションを解約しました。"
                );

        } catch (\Exception $e) {
            return redirect()->route('checkout.plan')
                ->with('error', '解約処理に失敗しました: ' . $e->getMessage());
        }
    }


    // 即時解約の場合（テスト用）■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
    // public function unsubscribe(Request $request)
    // {
    //     $user = $request->user();

    //     if (!$user->stripe_subscription_id) {
    //         return redirect()->route('checkout.plan')
    //             ->with('error', 'サブスクリプションが存在しません。');
    //     }

    //     \Stripe\Stripe::setApiKey(config('stripe.secret'));

    //     try {
    //         // ⭐ Stripeのサブスクリプションを即時キャンセル（期間末ではなく今すぐ停止）
    //         $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
    //         $subscription->cancel(); // ←これが即時解約

    //         // 即時キャンセルされた日時を取得
    //         $canceledAt = $subscription->canceled_at 
    //             ? date('Y-m-d H:i:s', $subscription->canceled_at)
    //             : now()->format('Y-m-d H:i:s');

    //         // DB更新
    //         $user->stripe_status = 'canceled';
    //         $user->stripe_canceled_at = $canceledAt;
    //         $user->save();

    //         return redirect()->route('checkout.plan')
    //             ->with('success', "サブスクリプションを即時解約しました（{$canceledAt} に解約完了）。");

    //     } catch (\Exception $e) {
    //         return redirect()->route('checkout.plan')
    //             ->with('error', '解約処理に失敗しました: ' . $e->getMessage());
    //     }
    // }
    
    // ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■

    public function showCancelDate()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 例：ユーザーのsubscription_idを取得
        $user = auth()->user();
        $subscriptionId = $user->stripe_subscription_id; // 保存してあると仮定

        $subscription = Subscription::retrieve($subscriptionId);

        // 解約予定日（キャンセル予約されている場合）
        $cancelAt = $subscription->cancel_at ? date('Y-m-d H:i:s', $subscription->cancel_at) : null;

        // 即時解約された場合（すでにキャンセル済み）
        $canceledAt = $subscription->canceled_at ? date('Y-m-d H:i:s', $subscription->canceled_at) : null;

        return view('profile.cancel-date', compact('cancelAt', 'canceledAt'));
    }


    // 解約取り消し処理
    public function cancelCancellation(Request $request)
    {
        $user = auth()->user();

        if (!$user->stripe_subscription_id) {
            return back()->with('error', 'サブスクリプションが見つかりません。');
        }

        // Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::setApiKey(config('stripe.secret'));


        try {
            // Stripe側でキャンセル予定を解除
            $subscription = Subscription::update(
                $user->stripe_subscription_id,
                ['cancel_at_period_end' => false]
            );

            // DB更新
            $user->stripe_status = $subscription->status; // 例: 'active'
            $user->stripe_canceled_at = null;
            $user->save();

            // return back()->with('success', '解約の取り消しが完了しました。');
            return redirect()
                ->route('checkout.cancel_cancellation_success')
                ->with('success', '解約の取り消しが完了しました。');
        } catch (\Exception $e) {
            return back()->with('error', '処理に失敗しました: ' . $e->getMessage());
        }
    }

}
