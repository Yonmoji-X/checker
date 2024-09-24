<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- iPhoneでのスタンドアロン表示を可能にする -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <!-- ステータスバーのスタイル -->
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- iOSでPWAアイコンとして使う画像 -->
        <link rel="apple-touch-icon" href="/images/app-icon-192.png">
        <!-- スタートアップ時の画面色 -->
        <meta name="apple-mobile-web-app-title" content="AC-Sync">

        <title>{{ $title ?? 'AC-Sync' }}</title>

        <!-- Manifestファイルへのリンク -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Service Worker登録 -->
        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ asset('service-worker.js') }}').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }).catch(function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
