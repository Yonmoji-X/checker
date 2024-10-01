<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- iPhoneでのスタンドアロン表示を可能にする -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- ステータスバーのスタイル -->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- iOSでPWAアイコンとして使う画像 -->
    <link rel="apple-touch-icon" href="/checker/images/app-icon-180.png"> <!-- 180x180 アイコン -->
    <!-- テーマカラーの指定 -->
    <meta name="theme-color" content="#3300cc"> <!-- テーマカラー -->
    <!-- スタートアップ時の画面色 -->
    <meta name="apple-mobile-web-app-title" content="AC-Sync">

    <!-- iOS向けのスタート画面画像を設定（必要に応じて） -->
    <link rel="apple-touch-startup-image" href="/checker/images/startup-640x1136.png" media="(device-width: 320px)">
    <link rel="apple-touch-startup-image" href="/checker/images/startup-750x1334.png" media="(device-width: 375px)">
    <link rel="apple-touch-startup-image" href="/checker/images/startup-1125x2436.png" media="(device-width: 375px) and (-webkit-device-pixel-ratio: 3)">

    <title>{{ $title ?? 'AC-Sync' }}</title>

    <!-- Manifestファイルへのリンク -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ViteでCSSとJSをリンク -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Service Worker登録 -->
    <style>
        html {
            background-color: #3300cc; /* ビューポート外の背景色 */
        }
    </style>
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
