<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 現在ログインしているユーザーが一般ユーザーの場合
        if ($user->role === 'user') {
            // groupテーブルから現在のユーザーIDに関連するadmin_idを取得
            $group = Group::where('user_id', $user->id)->first();

            // admin_idを使って管理者情報を取得
            if ($group) {
                $admin = User::find($group->admin_id);
            } else {
                $admin = null;
            }
        } else {
            $admin = null;
        }

        return view('dashboard', compact('admin'));
    }
}
