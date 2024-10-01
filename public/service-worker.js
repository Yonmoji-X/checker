// キャッシュ名とキャッシュするファイルのリスト
var CACHE_NAME = 'pwa-sample-caches';
var urlsToCache = [
    "/checker/",                        // アプリケーションのルート
    "/checker/index.php",               // メインエントリーポイント
    "/checker/build/assets/app-DZp4A-7N.css",  // CSSファイル
    "/checker/build/assets/app-CH09qwMe.js",   // JSファイル
    "/checker/images/app-icon-192.png",       // アイコン画像
    "/checker/manifest.json",           // マニフェストファイル
    "/checker/favicon.ico"              // Favicon
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

// サービスワーカーの登録処理
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/checker/service-worker.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            })
            .catch(function(error) {
                console.log('ServiceWorker registration failed: ', error);
            });
    });
}
