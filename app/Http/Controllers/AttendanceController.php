<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // ログインユーザーのメンバーだけを取得
        $members = Member::where('user_id', $userId)->latest()->get();

        // ログインユーザーの勤怠データだけを取得
        $attendances = Attendance::where('user_id', $userId)
            ->with('user', 'member') // ユーザーとメンバーのリレーションをロード
            ->latest()
            ->get();

        // ビューにデータを渡す
        return view('attendances.index', compact('attendances', 'members'));
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
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        // 勤怠データを削除
        $attendance->delete();
        return redirect()->route('attendances.index');
    }
}
