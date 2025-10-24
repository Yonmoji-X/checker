<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            購入完了
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @php
                $user = auth()->user();

                // Stripe設定ファイルから全プラン取得
                $plans = config('stripe.plans_list');

                // 現在のユーザーのプラン情報を取得
                $currentPlan = collect($plans)->firstWhere('stripe_plan', $user->stripe_plan);

                // 該当プランがなければ「未購入」扱い
                $planName = $currentPlan['name'] ?? '未購入';
                $planBg   = $currentPlan['bg'] ?? 'bg-gray-100 dark:bg-gray-800';
                $planText = $currentPlan['text'] ?? 'text-gray-700 dark:text-gray-300';

                // ステータスマップ
                $statusMap = [
                    'active' => '有効',
                    'past_due' => '支払い遅延',
                    'canceled' => 'キャンセル済み',
                    'unpaid' => '未払い',
                    null => '未設定',
                ];
                $statusText = $statusMap[$user->stripe_status] ?? '未設定';
            @endphp

            <div class="{{ $planBg }} p-8 rounded-2xl shadow-lg text-center transition duration-300">
                <h3 class="text-3xl font-extrabold {{ $planText }} mb-6">
                    決済が完了しました！
                </h3>

                <p class="{{ $planText }} mb-6">
                    ご購入ありがとうございます。すぐにご利用を開始できます。
                </p>

                <div class="space-y-4 text-center mb-6">
                    <div>
                        <span class="font-semibold {{ $planText }}">プラン名:</span>
                        <span class="text-2xl font-bold block mt-1 {{ $planText }}">{{ $planName }}</span>
                    </div>
                    <div>
                        <span class="font-semibold {{ $planText }}">支払いステータス:</span>
                        <span class="{{ $planText }} font-medium">{{ $statusText }}</span>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" 
                   class="{{ $currentPlan['button'] ?? 'bg-gray-500 hover:bg-gray-600 text-white' }} py-3 px-8 rounded-xl font-semibold transition">
                    ダッシュボードへ
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
