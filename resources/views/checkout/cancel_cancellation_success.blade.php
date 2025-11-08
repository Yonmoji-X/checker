<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            解約取り消し完了
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                <h3 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mb-4">
                    サブスクリプションの解約を取り消しました
                </h3>

                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    ご利用のサブスクリプションは引き続き有効です。<br>
                    今後もサービスを継続してご利用いただけます。
                </p>

                <a href="{{ route('dashboard') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-xl transition">
                    ダッシュボードへ戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
