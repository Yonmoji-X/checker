<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// プロジェクトルートに.envおいているローカルはそれ参照
// bitnamiに置いているサーバーはそれ参照

// --------------------------
// .envの読み込み（条件付き）
// --------------------------
$rootEnv = dirname(__DIR__) . '/.env';
$bitnamiEnv = '/opt/bitnami/env_files/checker_env/.env';

if (file_exists($rootEnv)) {
    // 通常のプロジェクトルートの.env
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} elseif (file_exists($bitnamiEnv)) {
    // Bitnami用プロジェクト外.env
    $dotenv = Dotenv\Dotenv::createImmutable('/opt/bitnami/env_files/checker_env');
    $dotenv->load();
}

// --------------------------
// Laravel アプリ設定
// --------------------------
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



// プロジェクトルートに.envおいている場合の過去のやつ
// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware) {

//     })
//     ->withExceptions(function (Exceptions $exceptions) {
//         //
//     })->create();
