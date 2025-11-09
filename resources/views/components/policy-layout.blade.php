@props(['title' => 'ポリシー'])

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ポリシー.' }} - SafeTimeCard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/app-icon-180.png">
    <meta name="theme-color" content="#111827">
    <meta name="apple-mobile-web-app-title" content="SafeTimeCard">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html { background-color: #f9fafb; }
    </style>
</head>
<!-- <body class="bg-gray-50 text-gray-800 font-sans"> -->
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">


    <!-- ヘッダー-->
    <header class="bg-indigo-600 text-white shadow-md p-4 flex items-center justify-between">
        <button onclick="history.back()" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-700 rounded text-white transition">
            ← 戻る
        </button>
        <h1 class="text-2xl font-bold text-center flex-1">{{ $title ?? 'ポリシー' }}</h1>
        <div class="w-12"></div>
    </header>

    <!-- メイン -->
     <main class="max-w-4xl mx-auto p-6 mt-6 bg-white rounded shadow space-y-6 flex-grow">
        {{ $slot }}
    </main>


    <!-- フッター -->
    <footer class="bg-gray-200 text-gray-700 p-4 mt-12 text-center text-sm">
        &copy; {{ date('Y') }} SafeTimeCard. All Rights Reserved.
    </footer>

</body>
</html>
