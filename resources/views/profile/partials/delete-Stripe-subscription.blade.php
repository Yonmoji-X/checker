<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('サブスクリプションの解約') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('サブスクリプションの解約を行うと、アプリの使用を継続できなくなります。') }}
        </p>
    </header>

    <a href="{{ route('checkout.unsubscribe') }}" class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition mt-4 inline-block">
    解約手続きへ
    </a>

</section>