{{-- resources/views/auth/two-factor-challenge.blade.php --}}

@php
    // ヘッダーを設定（未定義ならデフォルト）
    $header = $header ?? '二段階認証';

    // ユーザー情報が必要なコンポーネントに渡すための変数
    $user = auth()->user() ?? null;
@endphp

<x-app-layout :user="$user">
    <x-slot name="header">
        {{ $header }}
    </x-slot>

    <div class="max-w-md mx-auto mt-16 p-6 bg-white dark:bg-gray-800 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">{{ $header }}</h2>

        <p class="mb-4 text-gray-600 dark:text-gray-300">
            二段階認証コードを入力してください。
        </p>
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            認証コードとは、認証アプリ（Google Authenticator）で生成されたコードです。
        </p>

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">認証コード</label>
                <input id="code" name="code" type="text" required autofocus
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    認証
                </button>

                @if(session('two_factor_error'))
                    <span class="text-red-500 text-sm">{{ session('two_factor_error') }}</span>
                @endif
            </div>
        </form>

        <hr>

        <h3 class="mt-4 text-gray-900 dark:text-gray-100">認証アプリ（Google Authenticator）</h3>
        <div class="flex gap-4 mt-2">
            <div class="text-center">
                <p class="text-xs mb-1">iOS / App Store</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://apps.apple.com/app/google-authenticator/id388497605" alt="iOS QR">
            </div>
            <div class="text-center">
                <p class="text-xs mb-1">Android / Google Play</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" alt="Android QR">
            </div>
        </div>

        <hr>

        <h3 class="mt-4 text-gray-900 dark:text-gray-100">認証情報が認証アプリに登録されていない場合</h3>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            ２段階認証の設定時に、お使いのPCに<span class="font-bold">SafeTimeCard_2FA.pdf</span>というファイルがダウンロードされております。このファイルにはQRコードが記載されております。QRコードを認証アプリでスキャンして登録を行ってください。
        </p>

    </div>
</x-app-layout>
