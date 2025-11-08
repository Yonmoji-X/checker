<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            サブスクリプションの解約
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ $planName }} プランを解約しますか？
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                解約しても次回請求日までは利用可能です。
                解約を取り消す場合は、期間中に「解約の取り消し」ボタンからいつでも復帰できます。
            </p>

            <form method="POST" action="{{ route('checkout.unsubscribe.post') }}">
                @csrf
                <x-danger-button>
                    {{ __('解約を確定する') }}
                </x-danger-button>
            </form>

            <a href="{{ route('checkout.plan') }}"
               class="inline-block mt-4 text-gray-600 dark:text-gray-400 hover:underline">
               戻る
            </a>
        </div>
    </div>
</x-app-layout>
