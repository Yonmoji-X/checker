<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            購入プラン
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($planName)
            @php
                $bgColor = $planName === 'フリープラン' ? 'bg-green-50 dark:bg-green-900' : 'bg-blue-50 dark:bg-blue-900';
                $textColor = $planName === 'フリープラン' ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400';
            @endphp

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
                        @php
                            $statusMap = [
                                // Subscription 系
                                'active' => '有効',
                                'trialing' => 'トライアル中',
                                'past_due' => '支払い遅延',
                                'canceled' => 'キャンセル済み',
                                'unpaid' => '未払い',
                                'incomplete' => '未完了',
                                'incomplete_expired' => '期限切れ',

                                // Checkout（支払い系）
                                'paid' => '支払い済み',
                                'succeeded' => '支払い済み',
                                'requires_payment_method' => '支払い方法未設定',

                                null => '未設定',
                            ];

                            $statusText = $statusMap[$status] ?? '未設定';
                        @endphp
                        <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $statusText }}</span>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('checkout') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-xl font-semibold transition">
                        プランを変更する
                    </a>
                </div>
            </div>

            @else
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
