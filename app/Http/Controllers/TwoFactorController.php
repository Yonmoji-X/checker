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
}
