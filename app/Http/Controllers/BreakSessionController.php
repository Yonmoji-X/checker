<?php

namespace App\Http\Controllers;

use App\Models\BreakSession;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\Group;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BreakSessionController extends Controller
{
    /**
     * リソースの一覧を表示します。
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $userId = $user->id;
        } else {
            $userId = Group::where('user_id', $user->id)->value('admin_id');
        }

        // ログインユーザーに関連するBreakSessionを取得し、関連するMemberをロード
        $breaksessions = BreakSession::where('user_id', $userId)
                                    ->with('member')  // memberリレーションをロード
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('breaksessions.index', compact('breaksessions'));
    }



    /**
     * 新しいリソースを作成するためのフォームを表示します。
     */
    public function create()
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // 現在のログインユーザー情報を取得
        $currentUser = Auth::user();

        // ユーザーが「user」ロールの場合
        if ($currentUser->role === 'user') {
            // groupテーブルから現在のユーザーIDに関連するadmin_idを取得
            $group = Group::where('user_id', $userId)->first();

            // groupが存在すれば、admin_idを$userIdとして使用
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // 該当するユーザーのメンバーリストを取得
        $members = Member::where('user_id', $userId)->latest()->get();

        // ビューにデータを渡す
        return view('breaksessions.create', compact('members'));
    }

    /**
     * 新しく作成したリソースをストレージに保存します。
     */
    public function store(Request $request)
    {
        // 入力データのバリデーション
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // 出勤データ（attendance）を取得
        $attendance = Attendance::where('member_id', $request->member_id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', '出勤データが見つかりません。');
        }

        // 現在の認証済みユーザーを取得
        $user = Auth::user();
        $userId = $user->role === 'admin' ? $user->id : Group::where('user_id', $user->id)->value('admin_id');
        $currentTimestamp = Carbon::now()->setTimezone('Asia/Tokyo');

        // 休憩開始処理の前に、既に休憩中かどうかを確認
        $activeBreakSession = BreakSession::where('member_id', $request->member_id)
            ->whereNotNull('break_in')
            ->whereNull('break_out')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($request->input('action') === 'break_in') {
            if ($activeBreakSession) {
                return redirect()->back()->with('error', '既に休憩中です。休憩を終了してから再度お試しください。');
            }

            // 休憩開始処理
            BreakSession::create([
                'user_id' => $userId,
                'created_by' => $user->id,
                'member_id' => $request->member_id,
                'attendance_id' => $attendance->id,
                'break_in' => $currentTimestamp,
            ]);

            return redirect()->route('attendances.index')->with('success', '休憩が開始されました。');
            // return redirect()->route('breaksessions.index')->with('success', '休憩が開始されました。');
        } elseif ($request->input('action') === 'break_out') {
            // 休憩終了処理
            if (!$activeBreakSession) {
                return redirect()->back()->with('error', '休憩開始データが見つかりません。');
            }

            // `break_in` を東京時間として取得
            $breakInTime = Carbon::parse($activeBreakSession->break_in)->setTimezone('Asia/Tokyo');
            $breakOutTime = $currentTimestamp;

            // 休憩終了の時間を更新し、休憩時間（分）を計算
            $activeBreakSession->update([
                'break_out' => $breakOutTime,
                'break_duration' => $breakInTime->diffInMinutes($breakOutTime),
            ]);

            // return redirect()->route('breaksessions.index')->with('success', '休憩が終了しました。');
            return redirect()->route('attendances.index')->with('success', '休憩が終了しました。');
        }

        return redirect()->back()->with('error', '無効な操作です。');
    }



    /**
     * 指定されたリソースを表示します。
     */
    public function show(BreakSession $breakSession)
    {
        // 休憩セッションの詳細を表示
        return view('breaksessions.show', compact('breakSession'));
    }

    /**
     * 指定されたリソースを編集するためのフォームを表示します。
     */
    public function edit(BreakSession $breakSession)
    {
        // 休憩セッションの編集フォームを表示
        return view('breaksessions.edit', compact('breakSession'));
    }

    /**
     * 指定されたリソースをストレージで更新します。
     */
    public function update(Request $request, BreakSession $breakSession)
    {
        // 入力データのバリデーション
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id',
            'attendance_id' => 'required|exists:attendances,id',
            'break_in' => 'required|date',
            'break_out' => 'required|date|after:break_in', // 休憩終了時間は開始時間の後である必要がある
        ]);
        $breakIn = Carbon::parse($request->break_in)->setTimezone('Asia/Tokyo');
        $breakOut = Carbon::parse($request->break_out)->setTimezone('Asia/Tokyo');

        // 休憩時間を更新し、休憩時間（分）を再計算
        $breakSession->update([
            'member_id' => $request->member_id,
            'attendance_id' => $request->attendance_id,
            'break_in' => $breakIn,
            'break_out' => $breakOut,
            'break_duration' => $breakIn->diffInMinutes($breakOut),
        ]);

        return redirect()->route('breaksessions.index')->with('success', '休憩が更新されました。');
    }

    /**
     * 指定されたリソースをストレージから削除します。
     */
    public function destroy(BreakSession $breakSession)
    {
        // 休憩セッションを削除
        $breakSession->delete();
        return redirect()->route('breaksessions.index')->with('success', '休憩が削除されました。');
    }
}
