{{-- resources/views/auth/two-factor-challenge.blade.php --}}

@php
    $header = $header ?? '２段階認証';
    $user = auth()->user() ?? null;
@endphp

<x-app-layout :user="$user">
    <x-slot name="header">
        {{ $header }}
    </x-slot>

    <div class="max-w-md mx-auto mt-16 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        {{-- ページタイトル --}}
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ $header }}</h2>

        {{-- 説明 --}}
        <p class="mb-4 text-gray-600 dark:text-gray-300">
            ２段階認証コードを入力してください。<br>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                ※認証コードは、<strong>Google Authenticator</strong>などの認証アプリで生成されます。
            </span>
        </p>
        <!-- <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            認証コードとは、認証アプリ（Google Authenticator）で生成されたコードです。
        </p> -->

        {{-- ２段階認証コード入力フォーム --}}
        <form method="POST" action="{{ route('two-factor.login') }}" class="mb-6">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    認証コード
                </label>
                <input id="code" name="code" type="text" required autofocus
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex flex-col items-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow">
                    認証
                </button>

                @if(session('two_factor_error'))
                    <span class="text-red-500 text-sm mt-2">{{ session('two_factor_error') }}</span>
                @endif
            </div>


        </form>

        <hr class="border-gray-300 dark:border-gray-700 my-6">

        {{-- Google Authenticator QR --}}
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
            認証アプリ（Google Authenticator）
        </h3>
        <div class="flex gap-6 justify-center mt-2">
            <div class="text-center">
                <p class="text-xs font-medium mb-1">iOS / App Store</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://apps.apple.com/app/google-authenticator/id388497605" 
                     alt="iOS QR" class="mx-auto rounded-md shadow">
            </div>
            <div class="text-center">
                <p class="text-xs font-medium mb-1">Android / Google Play</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                     alt="Android QR" class="mx-auto rounded-md shadow">
            </div>
        </div>

        <hr class="border-gray-300 dark:border-gray-700 my-6">

        {{-- PDFダウンロードについて --}}
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">認証情報が認証アプリに登録されていない場合</h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            ２段階認証の設定時に、お使いのPCに
            <span class="font-bold text-gray-900 dark:text-gray-100">SafeTimeCard_2FA.pdf</span>
            というファイルがダウンロードされております。このファイルにはQRコードが記載されております。QRコードを認証アプリでスキャンして登録を行ってください。
        </p>
    </div>
</x-app-layout>
