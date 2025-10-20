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
                $planNameMap = [
                    env('STRIPE_PLAN_FREE') => 'フリープラン',
                    env('STRIPE_PLAN_STANDARD') => 'スタンダードプラン',
                    env('STRIPE_PLAN_PREMIUM') => 'プレミアムプラン',
                ];
                $planName = $planNameMap[$user->stripe_plan] ?? '未購入';

                $statusMap = [
                    'active' => '有効',
                    'past_due' => '支払い遅延',
                    'canceled' => 'キャンセル済み',
                    'unpaid' => '未払い',
                    null => '未設定',
                ];
                $statusText = $statusMap[$user->stripe_status] ?? '未設定';
            @endphp

            <div class="bg-blue-50 dark:bg-blue-900 p-8 rounded-2xl shadow-lg text-center">
                <h3 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-6">
                    決済が完了しました！
                </h3>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    ご購入ありがとうございます。すぐにご利用を開始できます。
                </p>

                <div class="space-y-4 text-center mb-6">
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">プラン名:</span>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $planName }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">支払いステータス:</span>
                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $statusText }}</span>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-xl font-semibold transition">
                    ダッシュボードへ
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
