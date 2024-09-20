<?php

namespace App\Http\Controllers;

use App\Models\BreakSession;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreakSessionController extends Controller
{
    /**
     * リソースの一覧を表示します。
     */
    public function index()
    {
        $breaksessions = BreakSession::with('user')->latest()->get();
        return view('breaksessions.index', compact('breaksessions'));
    }

    /**
     * 新しいリソースを作成するためのフォームを表示します。
     */
    public function create()
    {
        return view('breaksessions.create');
    }

    /**
     * 新しく作成したリソースをストレージに保存します。
     */
    public function store(Request $request)
    {
        // 入力データのバリデーション
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'attendance_id' => 'required|exists:attendances,id',
            'break_in' => 'required|date',
            'break_out' => 'required|date|after:break_in',
        ]);

        // 現在の認証済みユーザーを取得
        $user = Auth::user();

        // BreakSessionのuser_idを決定
        if ($user->role === 'admin') {
            $userId = $user->id; // 管理者のID
        } else {
            // groupテーブルから関連するadmin_idを取得
            $group = Group::where('user_id', $user->id)->first();
            $userId = $group ? $group->admin_id : null; // グループが存在すればadmin_idを取得
        }

        // ブレークセッションの作成
        BreakSession::create([
            'user_id' => $userId,
            'created_by' => $user->id,
            'member_id' => $request->member_id,
            'attendance_id' => $request->attendance_id,
            'break_in' => $request->break_in,
            'break_out' => $request->break_out,
            'break_duration' => $request->break_out->diffInMinutes($request->break_in), // 休憩時間を計算
        ]);

        return redirect()->route('breaksessions.index')->with('success', '休憩が登録されました。');
    }

    /**
     * 指定されたリソースを表示します。
     */
    public function show(BreakSession $breakSession)
    {
        return view('breaksessions.show', compact('breakSession'));
    }

    /**
     * 指定されたリソースを編集するためのフォームを表示します。
     */
    public function edit(BreakSession $breakSession)
    {
        return view('breaksessions.edit', compact('breakSession'));
    }

    /**
     * 指定されたリソースをストレージで更新します。
     */
    public function update(Request $request, BreakSession $breakSession)
    {
        // 入力データのバリデーション
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'attendance_id' => 'required|exists:attendances,id',
            'break_in' => 'required|date',
            'break_out' => 'required|date|after:break_in',
        ]);

        // ブレークセッションの更新
        $breakSession->update([
            'member_id' => $request->member_id,
            'attendance_id' => $request->attendance_id,
            'break_in' => $request->break_in,
            'break_out' => $request->break_out,
            'break_duration' => $request->break_out->diffInMinutes($request->break_in), // 休憩時間を再計算
        ]);

        return redirect()->route('breaksessions.index')->with('success', '休憩が更新されました。');
    }

    /**
     * 指定されたリソースをストレージから削除します。
     */
    public function destroy(BreakSession $breakSession)
    {
        $breakSession->delete();
        return redirect()->route('breaksessions.index')->with('success', '休憩が削除されました。');
    }
}
