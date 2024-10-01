const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')   // JavaScriptファイルをビルド
   .css('resources/css/app.css', 'public/css') // CSSファイルをビルド
   .version(); // キャッシュバスティングを有効にする
