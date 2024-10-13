<?php

namespace App\Http\Controllers;

use App\Models\Attendance; // 勤怠データのモデル
use App\Models\Member;     // メンバーデータのモデル
use App\Models\BreakSession;
use App\Models\Group;      // グループデータのモデル
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 認証情報を扱うファサード

/*
注意事項
これにおいて、usersテーブルのroleカラムがadminではなくuserの場合は、groupテーブルのuser_idが現在ログインしているユーザーのidのデータを探し、そのデータのadmin_idカラムのidを$userIdの部分に入れるようにする。
*/

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        // リクエストから日付範囲を取得
        $startDate = '2000-01-01'; // すべてのデータの最初の可能な日付 (または任意の開始日)
        $endDate = now()->toDateString(); // 現在の日付まで

        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        $members = Member::where('user_id', $userId)->latest()->get();

        // 勤怠データを日付範囲でフィルタリング
        $attendances = Attendance::where('user_id', $userId)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('attendance_date', [$startDate, $endDate]);
            })
            ->with(['user', 'member', 'breakSessions'])
            ->latest()
            ->paginate(31);

        $jsonMembers = json_encode($members, JSON_UNESCAPED_UNICODE);

        // ビューにデータを渡す
        return view('attendances.index', compact('attendances', 'members', 'jsonMembers', 'startDate', 'endDate'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // ログインユーザーのメンバーだけを取得
        $members = Member::where('user_id', $userId)->latest()->get();

        return view('attendances.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 勤怠データを保存するコードを追加します
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'attendance' => 'required|string|max:255', // バリデーション
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->attendance = $request->attendance;
        $attendance->save();

        // セッションフラッシュメッセージを設定
        return redirect()->route('attendances.index')->with('success', '更新成功しました。');
    }



    /**
     * Remove the specified resource from storage.
     */
/**
 * Remove the specified resource from storage.
 */
public function destroy(Attendance $attendance)
{
    // まず関連するBreakSessionレコードを削除
    $attendance->breakSessions()->delete();

    // その後、Attendanceレコードを削除
    $attendance->delete();

    return redirect()->route('attendances.index')->with('success', 'Attendance and related BreakSessions deleted successfully.');
}
public function export(Request $request)
{
    // フォームから選択されたメンバーIDと日付範囲を取得
    $memberId = $request->input('member_id');
    $dateRange = $request->input('date_range');

    // 日付範囲をパースして開始日と終了日を取得
    if ($dateRange) {
        $dates = explode(' から ', $dateRange);
        $startDate = $dates[0];
        $endDate = $dates[1];
    } else {
        $startDate = null;
        $endDate = null;
    }

    // エクスポート処理
    return Excel::download(new AttendanceExport($memberId, $startDate, $endDate), '勤怠データ.xlsx');
}

}
