<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorAuthenticatedSessionController extends Controller
{
    /**
     * 二段階認証コードの入力フォーム表示
     */
    public function create()
    {
        return view('auth.two-factor-challenge');
    }

    /**
     * 二段階認証コードの処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (! $user) {
            throw ValidationException::withMessages([
                'code' => ['ユーザーが認証されていません。'],
            ]);
        }

        // Google Authenticator などの確認
        if (! app(\Laravel\Fortify\TwoFactorAuthenticationProvider::class)
                ->verify(auth()->user(), $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['認証コードが正しくありません。'],
            ]);
        }

        // 二段階認証完了マーク
        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        // ダッシュボードへリダイレクト
        return redirect()->intended(route('dashboard'));
    }
}
