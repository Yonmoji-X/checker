<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\BreakSession;
use App\Models\Group;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
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

        $query = Attendance::where('user_id', $userId)->with(['member', 'breakSessions']);

        if ($request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        $attendances = $query->whereBetween('attendance_date', [$startDate, $endDate])
                             ->orderByDesc('attendance_date')
                             ->get();

        return view('attendances.index', compact('members', 'attendances', 'startDate', 'endDate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['attendance' => 'required|string|max:255']);

        $attendance = Attendance::findOrFail($id);
        $attendance->attendance = $request->attendance;
        $attendance->save();

        return redirect()->route('attendances.index')->with('success', '更新成功しました。');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->breakSessions()->delete();
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', '削除成功しました。');
    }

    public function export(Request $request)
    {
        $memberId = $request->input('member_id');
        $dateRange = $request->input('date_range');

        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');

        if ($dateRange) {
            [$startDate, $endDate] = explode(' から ', $dateRange);
        }

        $memberName = $memberId ? Member::find($memberId)->name : '全員';

        return Excel::download(new AttendanceExport($memberId, $startDate, $endDate), "{$memberName}_勤怠データ.xlsx");
    }
}
