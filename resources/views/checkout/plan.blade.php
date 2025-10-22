<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            購入プラン
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @php
                // Stripe設定から全プラン取得
                $plans = collect(config('stripe.plans_list'));

                // 現在のユーザー情報
                $user = auth()->user();
                $currentStripePlan = $user->stripe_plan ?? null;

                // 現在のプラン情報を特定
                $currentPlan = $plans->firstWhere('stripe_plan', $currentStripePlan);

                // 未登録の場合（＝フリープラン or 未購入）
                if (!$currentPlan && empty($user->stripe_subscription_id)) {
                    $currentPlan = $plans->firstWhere('key', 'free');
                }

                // 表示用の変数設定
                $planName = $currentPlan['name'] ?? '未購入';
                $bgColor = $currentPlan['bg'] ?? 'bg-gray-50 dark:bg-gray-800';
                $textColor = $currentPlan['text'] ?? 'text-gray-700 dark:text-gray-300';
                $buttonClass = $currentPlan['button'] ?? 'bg-gray-600 hover:bg-gray-700 text-white';

                // ステータスの表示変換
                $statusMap = [
                    'active' => '有効',
                    'trialing' => 'トライアル中',
                    'past_due' => '支払い遅延',
                    'canceled' => 'キャンセル済み',
                    'unpaid' => '未払い',
                    'incomplete' => '未完了',
                    'incomplete_expired' => '期限切れ',
                    'paid' => '支払い済み',
                    'succeeded' => '支払い済み',
                    'requires_payment_method' => '支払い方法未設定',
                    null => '未設定',
                ];

                $statusText = $statusMap[$user->stripe_status ?? null] ?? '未設定';
            @endphp

            {{-- プラン情報がある場合 --}}
            @if($currentPlan)
                <div class="{{ $bgColor }} p-8 rounded-2xl shadow-lg">
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-6 text-center">
                        あなたの現在のプラン
                    </h3>

                    <div class="space-y-4 text-center">
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">プラン名:</span>
                            <span class="text-2xl font-bold {{ $textColor }}">{{ $planName }}</span>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">支払いステータス:</span>
                            <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $statusText }}</span>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('checkout') }}" 
                           class="{{ $buttonClass }} py-3 px-8 rounded-xl font-semibold transition">
                            プランを変更する
                        </a>
                    </div>
                </div>
            @else
                {{-- プラン未登録時 --}}
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl shadow-md text-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        現在購入中のプランはありません
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        以下からプランを選択してご利用を開始できます。
                    </p>
                    <a href="{{ route('checkout') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-xl font-semibold transition">
                        プラン一覧を見る
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
