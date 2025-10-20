<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('SafeTimeCard 無料プラン') }}
        </h2>
    </x-slot>

    <div class="py-12 text-center">
        <h3 class="text-2xl font-bold text-green-600 mb-4">無料プランが適用されました！</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-6">
            ありがとうございます。すぐにご利用を開始できます。
        </p>
        <a href="{{ route('dashboard') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-xl transition">
            ダッシュボードへ戻る
        </a>
    </div>
</x-app-layout>
