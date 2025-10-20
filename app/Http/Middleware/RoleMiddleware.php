<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/'); // 未ログイン
        }

        // 役割チェック
        if ($user->role !== $role) {
            return redirect('/'); // 役割違い
        }

        // 管理者でプラン未選択の場合
        if ($role === 'admin' && !$user->stripe_plan) {
            return redirect()->route('checkout.plan')->with('message', 'まずプランを選択してください');
        }

        return $next($request);
    }
}
