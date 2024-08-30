<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::with('user')->latest()->get(); // 'user' リレーションに修正
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:64',
            'email' => 'required|email|max:255', // 'email' バリデーションルールを追加
            'content' => 'required|max:255',
        ]);

        $data = $request->only([
            'name',
            'email',
            'content',
        ]);

        // ユーザーが認証済みであることを確認し、members リレーションを使用
        $request->user()->members()->create($data);

        return redirect()->route('members.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|max:64',
            'email' => 'required|email|max:255',
            'content' => 'required|max:255',
        ]);

        $member->update($request->only(['name', 'email', 'content']));

        return redirect()->route('members.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        
        $member->delete();
        return redirect()->route('members.index');
    }
}
