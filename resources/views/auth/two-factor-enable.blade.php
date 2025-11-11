<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            二段階認証設定
        </h2>
    </x-slot>

    <div class="p-6">
        @if (session('status') === 'two-factor-enabled')
            <p class="text-green-600 font-semibold mb-4">二段階認証を有効化しました。</p>
        @endif

        @if ($qrCodeSvg)
            <div class="mb-4">
                <p>以下のQRコードを認証アプリでスキャンしてください。</p>
                <div class="mt-3">{!! $qrCodeSvg !!}</div>
            </div>

            <div class="mb-4">
                <h3 class="font-semibold">リカバリーコード</h3>
                <ul class="mt-2 text-gray-700">
                    @foreach ($recoveryCodes as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>
            </div>

            <form method="POST" action="{{ route('two-factor.disable') }}">
                @csrf
                @method('DELETE')
                <x-primary-button>二段階認証を無効化</x-primary-button>
            </form>
        @else
            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <x-primary-button>二段階認証を有効化</x-primary-button>
            </form>
        @endif
    </div>
</x-app-layout>
