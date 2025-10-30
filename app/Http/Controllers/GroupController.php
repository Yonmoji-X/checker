<?php
// app/Http/Controllers/GroupController.php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; //←追記

class GroupController extends Controller
{
    public function index()
    {
        Gate::authorize('isAdmin');//←追記
        $userId = Auth::id();
        $groups = Group::where('admin_id', $userId)->with('user')->latest()->get();
        // $users = User::where('user_id', $userId)->latest()->get();
        return view('groups.index', compact('groups'));

    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        Gate::authorize('isAdmin');//←追記
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $adminId = Auth::id();

        // すでに登録済み（同じ管理者のグループ内）
        if (Group::where('admin_id', $adminId)->where('email', $user->email)->exists()) {
            return redirect()->back()->with('error', 'このユーザーはすでに登録されています。');
        }

        // 他の管理者に紐づけられているかチェック
        $existingGroup = Group::where('email', $user->email)->first();
        if ($existingGroup && $existingGroup->admin_id !== $adminId) {
            return redirect()->back()->with('error', 'すでにほかのアカウントと紐づけられているため、追加できません。');
        }

        Group::create([
            'admin_id' => $adminId,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('groups.index')->with('success', 'ユーザーを追加しました。');
    }

    public function bulkRemove(Request $request)
    {
        Gate::authorize('isAdmin');

        $ids = $request->input('group_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', '削除する項目を選択してください。');
        }

        // 選択されたグループのレコードを削除
        Group::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' 件をグループから外しました。');
    }

}
