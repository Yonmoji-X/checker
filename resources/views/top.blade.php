<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeTimeCard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-indigo-100 via-white to-indigo-50 min-h-screen flex flex-col">

    <!-- ヘッダー -->
    <header class="flex justify-between items-center px-8 py-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/app-icon-192.png') }}" alt="App Icon" class="w-12 h-12 rounded-full">
            <span class="text-2xl font-bold text-indigo-700">SafeTimeCard</span>
        </div>
        <nav class="flex gap-4">

        </nav>
    </header>

    <!-- ヒーロー -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-6">
        <!-- <h1 class="text-5xl md:text-6xl font-extrabold text-indigo-800 mb-4">シンプルな勤怠管理</h1> -->
        <h1
        class="font-extrabold text-indigo-800 mb-4 text-center leading-tight"
        style="font-size: clamp(2rem, 8vw, 3.5rem); white-space: nowrap;"
        >
        シンプルな勤怠管理
        </h1>

        <p class="text-lg md:text-xl text-indigo-700 mb-8 max-w-xl">SafeTimeCardで社員の出退勤をスマートに管理。クラウドでどこからでもアクセス可能です。</p>
        <div class="flex gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">ダッシュボード</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">ログイン</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-md border border-indigo-600 text-indigo-600 hover:bg-indigo-100 transition">新規登録</a>
                    @endif
                @endauth
            @endif
            <!-- <a href="{{ route('login') }}" class="px-6 py-3 rounded-lg bg-indigo-600 text-white text-lg font-semibold hover:bg-indigo-700 transition">ログイン</a>
            <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg border-2 border-indigo-600 text-indigo-600 text-lg font-semibold hover:bg-indigo-100 transition">新規登録</a> -->
        </div>
    </main>

    <!-- フッター -->
    <footer class="py-6 text-center text-indigo-600 text-sm border-t border-gray-200 dark:border-gray-700 dark:text-indigo-400">
        <p class="mb-2">&copy; 2025 SafeTimeCard. All rights reserved.</p>

        <div class="flex flex-wrap justify-center gap-x-2 gap-y-1 text-sm">
            <a href="{{ route('policy.terms') }}" class="hover:underline">利用規約</a>
            <span>・</span>
            <a href="{{ route('policy.privacy') }}" class="hover:underline">プライバシーポリシー</a>
            <span>・</span>
            <a href="{{ route('policy.business') }}" class="hover:underline">事業者情報</a>
            <span>・</span>
            <a href="{{ route('policy.cancel') }}" class="hover:underline">キャンセルポリシー</a>
            <span>・</span>
            <a href="{{ route('policy.refund') }}" class="hover:underline">返金ポリシー</a>
            <span>・</span>
            <a href="{{ route('policy.legal') }}" class="hover:underline">特定商取引法に基づく表記</a>
            <span>・</span>
            <a href="{{ route('policy.contact') }}" class="hover:underline">お問い合わせ</a>
        </div>
    </footer>


</body>
</html>
