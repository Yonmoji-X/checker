<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $members = Member::where('user_id', $userId)->latest()->get();
        // $attendances = Attendance::where('user_id', $userId)->with('user')->latest()->get();
        $attendances = Attendance::with('user','member')->latest()->get();
        return view('attendances.index', compact('attendances', 'members'));
        // return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $userId = Auth::id();
        $members = Member::where('user_id', $userId)->latest()->get();

        return view('attendances.create', compact('members'));
        // return view('attendances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
        $attendance->delete();
        return redirect()->route('attendances.index');
    }
}
