<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('サブスクリプションの管理') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('解約または解約の取り消しを行うことができます。') }}
        </p>
    </header>

    @if (session('success'))
        <div class="text-green-600 text-sm mt-2">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="text-red-600 text-sm mt-2">{{ session('error') }}</div>
    @endif

    @php
        $cancelDate = auth()->user()->stripe_canceled_at;
    @endphp

    @if ($cancelDate)
        <div class="mt-4 p-4 bg-yellow-100 dark:bg-yellow-900 rounded">
            <p class="text-yellow-800 dark:text-yellow-200">
                現在、<strong>{{ \Carbon\Carbon::parse($cancelDate)->format('Y年m月d日 H:i') }}</strong> に解約予定です。
            </p>
        </div>

        <form method="POST" action="{{ route('checkout.cancel_cancellation') }}" class="mt-4">
            @csrf
            <x-danger-button>
                {{ __('解約の取り消し') }}
            </x-danger-button>
        </form>
    @else
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            現在、サブスクリプションは有効です。
        </p>
        <a href="{{ route('checkout.unsubscribe') }}"
            class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition mt-4 inline-block">
            {{ __('解約手続きへ') }}
        </a>
    @endif
</section>
