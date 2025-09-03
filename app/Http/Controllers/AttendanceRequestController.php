<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\Member;
use App\Models\BreakSession;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceRequestController extends Controller
{
    /**
     * 管理者一覧
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        $requests = AttendanceRequest::with('member', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('attendancerequests.index', compact('requests'));
    }

    /**
     * 作成フォーム
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

        // 該当するユーザーのメンバーリストを取得（is_visible フィルターも必要なら追加）
        $members = Member::where('user_id', $userId)
            ->where('is_visible', 1)
            ->latest()
            ->get();

        // ビューにデータを渡す
        return view('attendancerequests.create', compact('members'));
    }


    /**
     * 保存処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'attendance_date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'break_minutes' => 'nullable|integer',
            'remarks' => 'nullable|string|max:255',
        ]);

        AttendanceRequest::create([
            'user_id' => Auth::id(),
            'member_id' => $request->member_id,
            'attendance_date' => $request->attendance_date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'break_minutes' => $request->break_minutes,
            'remarks' => $request->remarks,
            'status' => 'pending', // 初期ステータス
        ]);

        if (Auth::user()->role == 'admin') {
            return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を作成しました。');
        }
        return redirect()->route('attendancerequests.create')->with('success', '勤怠申請を作成しました。');
    }

    /**
     * 編集フォーム
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, '編集権限がありません。');
        }

        $requestData = AttendanceRequest::findOrFail($id);
        $members = Member::all();
        return view('attendancerequests.edit', compact('requestData', 'members'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, '編集権限がありません。');
        }

        $request->validate([
            'member_id' => 'required|exists:members,id',
            'attendance_date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'break_minutes' => 'nullable|integer',
            'remarks' => 'nullable|string|max:255',
        ]);

        $req = AttendanceRequest::findOrFail($id);

        // Attendance を削除（承認済み・未承認問わず）
        BreakSession::where('attendance_request_id', $req->id)->delete();
        Attendance::where('attendance_request_id', $req->id)->delete();

        // AttendanceRequest を更新
        $req->update([
            'member_id' => $request->member_id,
            'attendance_date' => $request->attendance_date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'break_minutes' => $request->break_minutes,
            'remarks' => $request->remarks,
        ]);

        // Attendance を再作成
        $attendance = Attendance::create([
            'user_id' => Auth::id(), // 管理者ID（申請時に保存されたものを利用）
            'member_id' => $req->member_id,
            'attendance_date' => $req->attendance_date,
            'clock_in' => $req->clock_in,
            'clock_out' => $req->clock_out,
            'attendance' => $req->remarks,
            'attendance_request_id' => $req->id,
            'is_post_request' => true,
        ]);

            // BreakSession 再作成
        if ($req->break_minutes) {
            BreakSession::create([
                'user_id' => Auth::id(),
                'created_by' => Auth::id(),
                'member_id' => $req->member_id,
                'attendance_id' => $attendance->id,
                'attendance_request_id' => $req->id,
                'break_duration' => $req->break_minutes,
                'break_in' => null,
                'break_out' => null,
            ]);
        }

        // ステータス更新
        if ($req->status === 'approved') {
            $req->update(['status' => 'approved']);
            $message = '勤怠申請を承認済みのまま更新しました。';
        } else {
            $req->update(['status' => 'approved']);
            $message = '勤怠申請を承認しました。';
        }

        return redirect()->route('attendancerequests.index')->with('success', $message);
    }

    /**
     * 承認処理
     */
    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, '承認権限がありません。');
        }

        $req = AttendanceRequest::findOrFail($id);

        BreakSession::where('attendance_request_id', $req->id)->delete();
        Attendance::where('attendance_request_id', $req->id)->delete();

        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'member_id' => $req->member_id,
            'attendance_date' => $req->attendance_date,
            'clock_in' => $req->clock_in,
            'clock_out' => $req->clock_out,
            'attendance' => $req->remarks,
            'attendance_request_id' => $req->id,
            'is_post_request' => true,
        ]);

        // BreakSession 作成（休憩申請がある場合）
        if ($req->break_minutes) {
            BreakSession::create([
                'user_id' => Auth::id(),
                'created_by' => Auth::id(),
                'member_id' => $req->member_id,
                'attendance_id' => $attendance->id,
                'attendance_request_id' => $req->id,
                'break_duration' => $req->break_minutes,
                'break_in' => null,   // 申請時は不要
                'break_out' => null,  // 申請時は不要
            ]);
        }
        $req->update(['status' => 'approved']);

        return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を承認しました。');
    }

    /**
     * 却下処理
     */
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, '却下権限がありません。');
        }

        $req = AttendanceRequest::findOrFail($id);

        BreakSession::where('attendance_request_id', $req->id)->delete();
        Attendance::where('attendance_request_id', $req->id)->delete();

        $req->update(['status' => 'rejected']);

        return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を却下しました。');
    }

    /**
     * 削除処理
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, '削除権限がありません。');
        }

        $req = AttendanceRequest::findOrFail($id);

        BreakSession::where('attendance_request_id', $req->id)->delete();
        Attendance::where('attendance_request_id', $req->id)->delete();

        $req->delete();

        return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を削除しました。');
    }
}
