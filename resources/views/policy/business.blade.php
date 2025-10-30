<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>事業者向け情報 - SafeTimeCard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style> html { background-color: #f9fafb; } </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- ヘッダー -->
    <!-- <header class="bg-gray-800 text-white shadow-md p-4 flex items-center justify-between">
        <button 
            onclick="history.back()" 
            class="px-3 py-1 bg-purple-500 hover:bg-purple-700 rounded text-white transition">
            ← 戻る
        </button>
        <h1 class="text-2xl font-bold text-center flex-1">事業者向け情報</h1>
        <div class="w-12"></div>
    </header> -->
    <header class="bg-indigo-600 text-white shadow-md p-4 flex items-center justify-between">
        <button onclick="history.back()" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-700 rounded text-white transition">
            ← 戻る
        </button>
        <h1 class="text-2xl font-bold text-center flex-1">事業者向け情報</h1>
        <div class="w-12"></div>
    </header>

    <!-- メイン -->
    <main class="max-w-4xl mx-auto p-6 space-y-6 mt-6 bg-white rounded shadow">
        <p class="text-gray-600">本アプリは個人開発によるサブスクリプション型勤怠管理サービス「SafeTimeCard」です。事業者向けの情報を以下に記載します。</p>

        <section>
            <h2 class="text-xl font-semibold mb-2">事業内容</h2>
            <p>SafeTimeCard は、勤怠管理とサブスクリプション管理を簡単に行えるサービスです。事業者の従業員管理の効率化に役立ちます。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">利用条件</h2>
            <p>当サービスは、登録事業者が利用することを前提としています。利用にあたっては、各種契約や規約を遵守してください。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">サポート情報</h2>
            <p>ご不明な点は以下の窓口までお問い合わせください。</p>
            <p class="font-mono text-gray-700">support@safetimecard.example.com</p>
        </section>
    </main>

    <!-- フッター -->
    <footer class="bg-gray-200 text-gray-700 p-4 mt-12 text-center text-sm">
        &copy; {{ date('Y') }} SafeTimeCard. All Rights Reserved.
    </footer>

</body>
</html>
