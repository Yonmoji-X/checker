<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter; // ログイン試行回数制限用
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException; 
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ロック確認
        $this->ensureIsNotRateLimited($request);

        try {
            $request->authenticate(); // ✅ Fortify 標準の認証処理
        } catch (ValidationException $e) {
            // 失敗時にカウントを増やす
            RateLimiter::hit($this->throttleKey($request), 60); // 60秒後に自動リセット
            throw $e;
        }

        // 成功時はカウントリセット
        RateLimiter::clear($this->throttleKey($request));
        
        // ✅ session regenerate は保持
        $request->session()->regenerate();

        $user = $request->user();

        // ✅ 二段階認証が有効でまだ確認していない場合はリダイレクト
        if ($user->two_factor_secret && ! $user->two_factor_confirmed_at) {
            // Fortify 標準の two-factor-challenge にリダイレクト
            return redirect()->route('two-factor.login'); // ルートは Fortify のルートに合わせる
        }

        // Remember Me チェック
        $remember = $request->filled('remember');

        // ✅ Auth::login() は不要。すでに authenticate() 内でログイン済み
        // Auth::login($user, $remember); ←削除

        // 管理者でプラン未選択の場合はプラン選択画面へ
        if ($user->role === 'admin' && !$user->stripe_plan) {
            return redirect()->route('checkout.plan')->with('message', 'まずプランを選択してください');
        }

        // 通常はダッシュボードへ
        return redirect()->intended(route('dashboard'));
    }

    // ロック中か確認
    protected function ensureIsNotRateLimited(Request $request)
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) { // 5回まで許可
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "ログイン試行が多すぎます。{$seconds} 秒後に再試行してください。",
        ]);
    }

    // ユーザーごとのキー生成
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
