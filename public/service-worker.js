// キャッシュ名とキャッシュするファイルのリスト
var CACHE_NAME = 'pwa-sample-caches-v5'; // バージョンを更新

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
            console.log('Opened cache');
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

// 新しいサービスワーカーの有効化と古いキャッシュの削除
self.addEventListener('activate', function(event) {
    var cacheWhitelist = [CACHE_NAME]; // 残したいキャッシュをリストに追加

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        // ホワイトリストにないキャッシュは削除
                        return caches.delete(cacheName);
                    }
                })
            );
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
