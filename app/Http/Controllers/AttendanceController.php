<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Group;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // index表示
    public function index(Request $request)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) $userId = $group->admin_id;
        }

        $members = Member::where('user_id', $userId)->where('is_visible', 1)->get();

        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $memberId = $request->member_id ?? null;

        $query = Attendance::where('user_id', $userId)->with(['member', 'breakSessions']);
        if ($memberId) $query->where('member_id', $memberId);

        $attendances = $query->whereBetween('attendance_date', [$startDate, $endDate])
                            ->orderByDesc('attendance_date')
                            ->paginate(5)
                            ->withQueryString();

        return view('attendances.index', compact('members', 'attendances', 'startDate', 'endDate'));
    }

    // Ajax用フィルタ
    public function filter(Request $request)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) $userId = $group->admin_id;
        }

        $memberId = $request->member_id ?? null;
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $query = Attendance::where('user_id', $userId)->with(['member', 'breakSessions']);
        if ($memberId) $query->where('member_id', $memberId);

        $attendances = $query->whereBetween('attendance_date', [$startDate, $endDate])
                            ->orderByDesc('attendance_date')
                            ->paginate(5)
                            ->withQueryString();

        $rows = view('attendances._table_rows', compact('attendances'))->render();
        $pagination = view('attendances._pagination', compact('attendances'))->render();

        return response()->json([
            'rows' => $rows,
            'pagination' => $pagination,
        ]);
    }

    // 備考更新
    public function update(Request $request, $id)
    {
        $request->validate(['attendance' => 'required|string|max:255']);

        $attendance = Attendance::findOrFail($id);
        $attendance->attendance = $request->attendance;
        $attendance->save();

        return redirect()->back()->with('success', '更新成功しました。');
    }

    // 削除
    public function destroy(Attendance $attendance)
    {
        $attendance->breakSessions()->delete();
        $attendance->delete();

        return redirect()->back()->with('success', '削除成功しました。');
    }

    // Excel出力
    public function export(Request $request)
    {
        $memberId = $request->input('member_id');
        $startDate = $request->input('start_date') ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->input('end_date') ?? now()->endOfMonth()->format('Y-m-d');

        $memberName = $memberId ? Member::find($memberId)->name : '全員';

        return Excel::download(new AttendanceExport($memberId, $startDate, $endDate), "{$memberName}_勤怠データ.xlsx");
    }
}
