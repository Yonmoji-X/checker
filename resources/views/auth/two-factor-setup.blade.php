<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            二段階認証セットアップ
        </h2>
    </x-slot>

    <div class="p-6">
        @if ($user->two_factor_secret)
            <div>
                <p>以下のQRコードを認証アプリでスキャンしてください。</p>
                <div class="mt-4">{!! $user->twoFactorQrCodeSvg() !!}</div>

                <h3 class="mt-4 font-bold">リカバリーコード</h3>
                <ul>
                    @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                        <li>{{ $code }}</li>
                    @endforeach
                </ul>

                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    @method('DELETE')
                    <x-primary-button class="mt-4">二段階認証を無効化</x-primary-button>
                </form>
            </div>
        @else
            <p>まだ二段階認証は有効化されていません。</p>
        @endif
    </div>
</x-app-layout>
