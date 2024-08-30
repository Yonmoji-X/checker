<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Template;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // レコードを最新のものから取得し、ユーザー情報も含める
        // =====================
        // $templates = Template::with('user')->latest()->get();
        // $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        // $members = Member::with('user')->latest()->get();
        // =====================
        // $records = Record::with('user')->latest()->get();
        // $templates = Template::with('user')->latest()->get();
        // $members = Member::with('user')->latest()->get();
        // $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        // return view('records.index', compact('records','templates', 'members', 'jsonTemplates'));
        // return view('records.index', compact('records', 'templates', 'members', 'jsonTemplates'));

        $userId = Auth::id();
        $records = Record::with('user')->latest()->get();
        $templates = Template::where('user_id', $userId)->latest()->get();
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $jsonRecords = json_encode($records, JSON_UNESCAPED_UNICODE);
        $members = Member::where('user_id', $userId)->latest()->get();
        return view('records.index', compact('records', 'templates', 'members', 'jsonTemplates', 'jsonRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // 現在のログインユーザーのIDを取得
        $userId = Auth::id();

        // 現在のユーザーに関連するテンプレートとメンバーを取得
        $templates = Template::where('user_id', $userId)->latest()->get();
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $members = Member::where('user_id', $userId)->latest()->get();

        return view('records.create', compact('templates', 'members', 'jsonTemplates'));
        // return view('records.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // バリデーション
        // dd($request->all());
        $validated = $request->validate([
            'member_id' => 'required|integer',
            'member_status' => 'required|boolean',
            'clock_status' => 'required|boolean',
            'template_id_*' => 'required|integer',
            'check_item_*' => 'nullable|boolean',
            'photo_item_*' => 'nullable|file|image|max:2048',
            'content_item_*' => 'nullable|string|max:255',
            'temperature_item_*' => 'nullable|numeric',
        ]);

        // template_id_の数だけ繰り返す

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'template_id_') === 0) {
                // インデックスを取得
                $index = str_replace('template_id_', '', $key);

                $maxId = Record::max('id') + 1;
                $head_id = $maxId - (int)$index;


                // レコードデータを作成
                $recordData = [
                    'user_id' => Auth::id(), // 現在のログインユーザーID
                    'member_id' => $request->input('member_id'),
                    'template_id' => $value,
                    'member_status' => $request->input('member_status'),
                    'clock_status' => $request->input('clock_status'),
                    'check_item' => $request->input("check_item_$index"),
                    'content_item' => $request->input("content_$index"),
                    'temperature_item' => $request->input("temperature_$index"),
                    'photo_item' => $request->hasFile("photo_$index")
                        ? $request->file("photo_$index")->store('photos', 'public')
                        : null,
                    'head_id' => $head_id,
                    // 'head_id' => $request->input("id - $index"),
                ];

                // レコードを作成
                Record::create($recordData);
                // $request->user()->records()->create($recordData);
            }
        }

        // レコード保存後、リダイレクト
        return redirect()->route('records.create');
        // return redirect('/records')->with('success', 'Record created successfully.');
    }




    /**
     * Display the specified resource.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\View\View
     */
    public function show(Record $record)
    {
        return view('records.show', ['record' => $record]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\View\View
     */
    public function edit(Record $record)
    {
        // 現在のログインユーザーのIDを取得
        $userId = Auth::id();

        // 現在のユーザーに関連するテンプレートを取得
        $templates = Template::where('user_id', $userId)->get();

        return view('records.edit', [
            'record' => $record,
            'templates' => $templates
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Record $record)
    {
        // バリデーションルールを定義
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'member_id' => 'required|integer',
            'template_id' => 'required|integer',
            'member_status' => 'required|boolean',
            'clock_status' => 'required|boolean',
            'check_item' => 'nullable|boolean',
            'photo_item' => 'nullable|file|image|max:2048',
            'content_item' => 'nullable|string|max:255',
            'temperature_item' => 'nullable|numeric',
        ]);

        // レコードを更新
        $record->update($validated);

        return redirect('/records')->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Record $record
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Record $record)
    {
        // dd($record->all());
        // レコードを削除
        Record::where('head_id', $record->id)->delete();
        return redirect()->route('templates.index');
        // return redirect()->back()->with('success', 'Head IDが' . $record->id . 'のレコードを削除しました。');
    }
    // $record->delete();
    // ここで、取得したidと一致するhead_idを持つ、レコードを全て削除したい。
    // return redirect('/records')->with('success', 'Record deleted successfully.');
}
