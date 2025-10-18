<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // リクエスト処理後のレスポンスを取得
        $response = $next($request);

        // セキュアな HTTP ヘッダを設定
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // Clickjacking 防止
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self';");
        $response->headers->set('X-Powered-By', ''); // サーバ情報を隠す
        $response->headers->set('Permissions-Policy', ''); // 必要に応じて追加
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Content-Security-Policy', "
            default-src 'self';        /* 基本は自分のサイトからのみ */
            script-src 'self';         /* JS は自分サイトからのみ */
            style-src 'self';          /* CSS は自分サイトからのみ */
            img-src 'self' data:;      /* 画像は自サイトと data URI */
            font-src 'self';           /* フォントも自サイトのみ */
            frame-ancestors 'self';    /* iframe に埋め込まれるのを自サイトのみに制限 */
        ");
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');





        return $response;
    }
}
