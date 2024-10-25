<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; //←roleがadminの人以外は入れない。

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //templatesと関連ユーザーの情報をとってくる。
        // $templates = Template::with('user')->latest()->get();
        // return view('templates.index',compact('templates'));
        // -------------------------------------------
        Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
        $userId = Auth::id();
        $templates = Template::where('user_id', $userId)->with('user')->latest()->get();
        return view('templates.index', compact('templates'));

        // $templates = Template::where('user_id', $userId)->with('user')->latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // dd($request->all());
    // バリデーション
    Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
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
        Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
        return view('templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        // dd($template->all());
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        //
        Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
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

        $template->update($data);
        return redirect()->route('templates.show', $template);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        //
        Gate::authorize('isAdmin'); //←roleがadminの人以外は入れない。
        // $template->delete();
        // return redirect()->route('templates.index');

        // #####################Template.php参照#####################
        // 危険NG→head_idのテンプレだったら詰む。
        // →recordデータのtemplate_idをnullありにして、viewで削除されましたってするくらいがいい。
        $template->records()->delete();
        $template->delete();
        return redirect()->route('templates.index');
        // #####################Template.php参照#####################
    }
    public function updateOrder(Request $request)
    {
        $sortedIDs = $request->input('sortedIDs'); // sortedIDsを取得

        // もしsortedIDsがnullまたは空であればエラーを返す
        if (empty($sortedIDs)) {
            return response()->json(['error' => 'No IDs provided.'], 400);
        }

        // データベースを更新するロジック
        foreach ($sortedIDs as $order => $id) {
            Template::where('id', $id)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }


}
