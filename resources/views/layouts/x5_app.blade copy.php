<!DOCTYPE html>
<html class="role-XX" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/app-icon-180.png">
    <!-- <link rel="apple-touch-icon" href="/checker/images/app-icon-180.png"> -->
    <meta name="theme-color" content="#111827">
    <meta name="apple-mobile-web-app-title" content="SafeTimeCard">

    <title>{{ $title ?? 'SafeTimeCard' }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])

    <style>
        html { background-color: #111827; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- ✅ Alpine.js 全体スコープ -->
    <div x-data="{ open: false }" class="min-h-screen flex flex-col md:flex-row relative transition-all duration-300">

        <!-- ✅ サイドバー -->
        @include('layouts.aside')

        <!-- ✅ メインコンテンツ -->
        <div
            :class="{
                'translate-x-64 md:translate-x-0': open,  // モバイル時のみ右スライド
            }"
            class="flex-1 flex flex-col relative transform transition-transform duration-300 ease-in-out"

        >
            <!-- Header -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow fixed w-full md:static z-30">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 h-14">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto mt-14 md:mt-0">
                {{ $slot }}
            </main>
        </div>

        <!-- ✅ 背景フェード（モバイル時のみ） -->
        <div
            x-show="open"
            @click="open = false"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden"
        ></div>
        
    </div>
    
    {{-- 🔹 plan未選択なら、強制選択 --}}
    @if(auth()->check() && auth()->user()->role === 'admin' && empty(auth()->user()->stripe_plan))
        <x-plan-modal />
    @endif

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
