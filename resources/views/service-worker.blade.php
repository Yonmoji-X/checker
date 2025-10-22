<!DOCTYPE html>
<html>
<head>
    <title>Service Worker</title>
</head>
<body>
<script>
    // キャッシュ名とキャッシュするファイルのリスト
    var CACHE_NAME = 'pwa-sample-caches';
    // var urlsToCache = [
    //     "/",                         // アプリケーションのルート
    //     "/index.php",                // メインエントリーポイント（index.php）
    //     "{{ asset('css/app.css') }}",       // LaravelでビルドされたCSSファイル
    //     "{{ asset('js/app.js') }}",         // LaravelでビルドされたJavaScriptファイル
    //     "/images/app-icon-192.png", // アイコン画像
    //     "/manifest.json",            // マニフェストファイル
    //     "/favicon.ico"               // Favicon
    // ];
    var urlsToCache = [
        "/checker/",                         // アプリケーションのルート
        "/checker/index.php",                // メインエントリーポイント（index.php）
        "{{ asset('css/app.css') }}",       // LaravelでビルドされたCSSファイル
        "{{ asset('js/app.js') }}",         // LaravelでビルドされたJavaScriptファイル
        "/checker/images/app-icon-192.png", // アイコン画像
        "/checker/manifest.json",            // マニフェストファイル
        "/checker/favicon.ico"               // Favicon
    ];

    // インストール処理
    self.addEventListener('install', function(event) {
        event.waitUntil(
            caches.open(CACHE_NAME).then(function(cache) {
                return cache.addAll(urlsToCache);
            })
        );
    });

    // リソースフェッチ時のキャッシュロード処理
    self.addEventListener('fetch', function(event) {
        event.respondWith(
            caches.match(event.request).then(function(response) {
                return response || fetch(event.request);
            })
        );
    });
</script>
</body>
</html>
