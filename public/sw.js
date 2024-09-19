// キャッシュ名とキャッシュするファイルのリスト
var CACHE_NAME = 'pwa-sample-caches';
var urlsToCache = [
    "/",
    "/css/style.css",
    "/js/app.js",
    "/images/app-icon-192.png"
];

// インストール処理
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches
            .open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

// リソースフェッチ時のキャッシュロード処理
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches
            .match(event.request)
            .then(function(response) {
                return response ? response : fetch(event.request);
            })
    );
});
