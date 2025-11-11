<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    // QRコード表示画面
    public function show()
    {
        $user = Auth::user();
        return view('auth.two-factor-confirm', [
            'user' => $user,
            'qrCode' => $user->twoFactorQrCodeSvg(), // Fortify の QR 生成メソッド
        ]);
    }

    // Confirm ボタン押下時
    public function confirm(Request $request)
    {
        $user = Auth::user();

        // パスワード確認などをしたい場合はここで検証
        // $request->validate(['password' => 'required|current_password']);

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return redirect()->route('dashboard')->with('status', '二段階認証が有効になりました');
    }

    // public function enable(Request $request)
    // {
    //     $user = $request->user();

    //     // 二段階認証を有効化（Laravel Fortifyの処理を呼び出す）
    //     $user->enableTwoFactorAuthentication(); // もしメソッドがない場合は Fortifyの処理をコピー

    //     // QRコードのSVGを取得
    //     $svg = $user->twoFactorQrCodeSvg();

    //     // SVGをJPGに変換
    //     $image = new \Imagick();
    //     $image->readImageBlob($svg);
    //     $image->setImageFormat('jpeg');

    //     $jpgData = $image->getImageBlob();

    //     // ダウンロードレスポンス
    //     return Response::make($jpgData, 200, [
    //         'Content-Type' => 'image/jpeg',
    //         'Content-Disposition' => 'attachment; filename="SafeTimeCard_2FA.jpg"',
    //     ]);
    // }
}
