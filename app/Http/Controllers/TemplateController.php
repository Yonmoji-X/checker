<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //templatesと関連ユーザーの情報をとってくる。
        $templates = Template::with('user')->latest()->get();
        return view('templates.index',compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // dd($request->all());
    // バリデーション
    $request->validate([
        'title' => 'required|max:64',
        'member_status' => 'required|boolean',
        'clock_status' => 'required|boolean',
        'has_check' => 'required|boolean',
        'has_photo' => 'required|boolean',
        'has_content' => 'required|boolean',
        'has_temperature' => 'required|boolean',
    ]);

    // リクエストからデータを取得
    $data = $request->only([
        'title',
        'member_status',
        'clock_status',
        'has_check',
        'has_photo',
        'has_content',
        'has_temperature'
    ]);

    // dd($request->user()->templates()->create($data));
    // ユーザーのテンプレートにデータを作成
    $request->user()->templates()->create($data);

    return redirect()->route('templates.index');
}


    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
        return view('templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        //
    }
}
