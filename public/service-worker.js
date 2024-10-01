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
