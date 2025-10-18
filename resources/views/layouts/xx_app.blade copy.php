<!DOCTYPE html>
<html class="role-XX" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- iPhoneでのスタンドアロン表示を可能にする -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/checker/images/app-icon-180.png">
    <meta name="theme-color" content="#111827">
    <meta name="apple-mobile-web-app-title" content="AC-Sync">

    <!-- iOS向けスタート画面 -->
    <link rel="apple-touch-startup-image" href="/checker/images/startup-640x1136.png" media="(device-width: 320px)">
    <link rel="apple-touch-startup-image" href="/checker/images/startup-750x1334.png" media="(device-width: 375px)">
    <link rel="apple-touch-startup-image" href="/checker/images/startup-1125x2436.png" media="(device-width: 375px) and (-webkit-device-pixel-ratio: 3)">

    <title>{{ $title ?? 'AC-Sync' }}</title>

    <!-- Manifest / Favicon -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Flatpickr / jQuery UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Vite CSS -->
    @vite(['resources/css/app.css'])

    <style>
        html { background-color: #111827; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <!-- <aside class="w-64 bg-gray-800 text-gray-100 flex flex-col justify-between"> -->
            <div>
                <!-- <div class="p-6 text-center border-b border-gray-700">
                    <span class="text-xl font-semibold">SafeTimeCard</span>
                </div> -->

                <!-- Navigation -->
                <nav class="mt-4 flex-1">
                    @include('layouts.aside')
                </nav>
            </div>

            <!-- Footer (ログアウトボタンとか) -->
            <!-- <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-md text-sm font-medium">
                        ログアウト
                    </button>
                </form>
            </div> -->
        <!-- </aside> -->

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @isset($header)
                <!-- <header class="bg-gray-800 shadow"> -->
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 h-14">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- JS ライブラリ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJ6U3FpE96l7k8hV2NHuCx7FyoQ3J+X2dH6EQ="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-KyZXEAg3QhqLMpG8r+Knujsl5+t9vVQ5JgJzy3R/yN8="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

    <!-- Vite JS -->
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('service-worker.js') }}')
            .then(reg => console.log('ServiceWorker registration successful:', reg.scope))
            .catch(err => console.log('ServiceWorker registration failed:', err));
        }
    </script>
</body>
</html>
