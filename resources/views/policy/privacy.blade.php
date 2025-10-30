<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プライバシーポリシー - SafeTimeCard</title>
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
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- ヘッダー -->
    <!-- <header class="bg-green-600 text-white shadow-md p-4 flex items-center justify-between">
        <button 
            onclick="history.back()" 
            class="px-3 py-1 bg-green-500 hover:bg-green-700 rounded text-white transition">
            ← 戻る
        </button>
        <h1 class="text-2xl font-bold text-center flex-1">プライバシーポリシー</h1>
        <div class="w-12"></div>
    </header> -->
    <header class="bg-indigo-600 text-white shadow-md p-4 flex items-center justify-between">
        <button onclick="history.back()" class="px-3 py-1 bg-indigo-500 hover:bg-indigo-700 rounded text-white transition">
            ← 戻る
        </button>
        <h1 class="text-2xl font-bold text-center flex-1">プライバシーポリシー</h1>
        <div class="w-12"></div>
    </header>

    <!-- メイン -->
    <main class="max-w-4xl mx-auto p-6 space-y-6 mt-6 bg-white rounded shadow">
        
        <p class="text-gray-600">本アプリは個人開発によるサブスクリプション型勤怠管理サービス「SafeTimeCard」です。以下に個人情報の取扱いについて説明いたします。</p>

        <section>
            <h2 class="text-xl font-semibold mb-2">個人情報の収集について</h2>
            <p>サービス提供に必要な範囲で、氏名、メールアドレス、電話番号などの個人情報を収集することがあります。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">個人情報の利用目的</h2>
            <ul class="list-disc list-inside space-y-1">
                <li>サービスの提供・運営のため</li>
                <li>お問い合わせへの対応のため</li>
                <li>サブスクリプション管理や請求処理のため</li>
                <li>新サービスやキャンペーンの案内のため</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">第三者提供について</h2>
            <p>法令に基づく場合を除き、本人の同意なく第三者に提供することはありません。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">安全管理</h2>
            <p>個人情報の漏洩、紛失、改ざんを防止するため、技術的および組織的に適切な安全管理措置を講じます。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">Cookieの使用について</h2>
            <p>当アプリでは、サービス向上のために必要な範囲でCookieを使用する場合があります。ブラウザ設定でCookieを拒否することも可能です。</p>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-2">お問い合わせ窓口</h2>
            <p>個人情報に関するお問い合わせは、以下のメールアドレスまでご連絡ください。</p>
            <p class="font-mono text-gray-700">privacy@safetimecard.example.com</p>
        </section>

        <section>
            <p class="text-sm text-gray-500 mt-4">※本プライバシーポリシーは予告なく変更されることがあります。最新の内容は本ページでご確認ください。</p>
        </section>

    </main>

    <!-- フッター -->
    <footer class="bg-gray-200 text-gray-700 p-4 mt-12 text-center text-sm">
        &copy; {{ date('Y') }} SafeTimeCard. All Rights Reserved.
    </footer>

</body>
</html>
