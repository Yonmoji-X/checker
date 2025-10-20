<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            購入キャンセル
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md">
            
            <h3 class="text-lg font-bold mb-4">購入はキャンセルされました</h3>

            <p>決済手続きを中止したため、購入は完了していません。</p>

            <div class="mt-6">
                <a href="{{ route('checkout') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-xl">
                    プラン一覧へ戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
