<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\Member;
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

        $requests = AttendanceRequest::with('member', 'user')->get();
        return view('attendancerequests.index', compact('requests'));
    }

    /**
     * 作成フォーム
     */
    public function create()
    {
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, '作成権限がありません。');
        // }

        $members = Member::all();
        return view('attendancerequests.create', compact('members'));
    }

    /**
     * 保存処理
     */
    public function store(Request $request)
    {
        // if (Auth::user()->role !== 'admin') {
        //     abort(403, '作成権限がありません。');
        // }

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
        $req->update([
            'member_id' => $request->member_id,
            'attendance_date' => $request->attendance_date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'break_minutes' => $request->break_minutes,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を更新しました。');
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

        // 既存の承認済みデータがあれば削除
        Attendance::where('attendance_request_id', $req->id)->delete();

        // 新しいデータをコピー
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

        // \Log::info('Created Attendance:', $attendance->toArray());
        // dd($attendance);
        // ステータス更新
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

        // 承認済みなら Attendance から削除
        Attendance::where('attendance_request_id', $req->id)->delete();

        // ステータス更新
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

        // 承認済みなら Attendance から削除
        Attendance::where('attendance_request_id', $req->id)->delete();

        $req->delete();

        return redirect()->route('attendancerequests.index')->with('success', '勤怠申請を削除しました。');
    }
}
