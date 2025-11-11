<div>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        二段階認証
    </h2>

    @if (auth()->user()->two_factor_secret)
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            二段階認証は現在有効です。
        </p>

        <div class="mt-4">
            {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </div>

        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                リカバリーコード
            </h3>
            <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>

        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            @method('DELETE')
            <x-primary-button class="mt-4">
                二段階認証を無効化
            </x-primary-button>
        </form>
    @else
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            二段階認証を有効にすると、ログイン時に認証アプリによる確認が必要になります。
        </p>

        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <x-primary-button class="mt-4">
                二段階認証を有効化
            </x-primary-button>
        </form>
    @endif
</div>
