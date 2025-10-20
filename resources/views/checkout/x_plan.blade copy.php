<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            購入プラン
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($planName)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
                <h3 class="text-2xl font-bold mb-4">あなたの現在のプラン</h3>

                <div class="space-y-4">
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">プラン名:</span>
                        <span class="text-lg text-blue-600 dark:text-blue-400">{{ $planName }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">支払いステータス:</span>
                        @php
                            $statusMap = [
                                'active' => '有効',
                                'past_due' => '支払い遅延',
                                'canceled' => 'キャンセル済み',
                                'unpaid' => '未払い',
                                null => '未設定',
                            ];
                            $statusText = $statusMap[$status] ?? '未設定';
                        @endphp
                        <span class="text-gray-800 dark:text-gray-200">{{ $statusText }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('checkout') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-xl transition">
                        プランを変更する
                    </a>
                </div>
            </div>

            @else
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center">
                <p class="text-gray-700 dark:text-gray-300 mb-4">現在購入中のプランはありません。</p>
                <a href="{{ route('checkout') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-xl transition">
                    プラン一覧を見る
                </a>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
