<?php
namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Attendance;
use App\Models\Template;
use App\Models\Member;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/*
注意事項
これにおいて、usersテーブルのroleカラムがadminではなくuserの場合は、groupテーブルのuser_idが現在ログインしているユーザーのidのデータを探し、そのデータのadmin_idカラムのidを$userIdの部分に入れるようにする。
*/

class RecordController extends Controller
{
    public function index()
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // 現在のログインユーザー情報を取得
        $currentUser = Auth::user();

        // ユーザーがuser roleの場合
        if ($currentUser->role === 'user') {
            // groupテーブルから現在のユーザーIDに関連するadmin_idを取得
            $group = Group::where('user_id', $userId)->first();

            // groupが存在すれば、admin_idを$userIdとして使用
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // 指定した$userIdでレコードを取得
        $records = Record::with('user')->where('user_id', $userId)->latest()->get();
        $templates = Template::where('user_id', $userId)->latest()->get();

        // JSONエンコード
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $jsonRecords = json_encode($records, JSON_UNESCAPED_UNICODE);

        // メンバーを取得
        $members = Member::where('user_id', $userId)->latest()->get();

        // メンバー情報をJSON形式でエンコード
        $jsonMembers = json_encode($members, JSON_UNESCAPED_UNICODE);

        // ビューにデータを渡す
        return view('records.index', compact('records', 'templates', 'members', 'jsonTemplates', 'jsonRecords', 'jsonMembers'));
    }


    public function create()
    {
        // 現在のユーザーIDを取得
        $userId = Auth::id();

        // 現在のログインユーザー情報を取得
        $currentUser = Auth::user();

        // ユーザーがuser roleの場合
        if ($currentUser->role === 'user') {
            // groupテーブルから現在のユーザーIDに関連するadmin_idを取得
            $group = Group::where('user_id', $userId)->first();

            // groupが存在すれば、admin_idを$userIdとして使用
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // 指定した$userIdでテンプレートとメンバーを取得
        $templates = Template::where('user_id', $userId)->latest()->get();
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $members = Member::where('user_id', $userId)->latest()->get();

        // ビューにデータを渡す
        return view('records.create', compact('templates', 'members', 'jsonTemplates'));
    }

    public function store(Request $request)
    {
        // バリデーション
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

        // 現在のユーザーIDを取得
        $userId = Auth::id();
        $createdById = $userId;

        // 現在のログインユーザー情報を取得
        $currentUser = Auth::user();

        // ユーザーがuser roleの場合、admin_idに切り替え
        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // トランザクションの開始
        DB::beginTransaction();

        try {
            // Recordテーブルにデータを保存
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'template_id_') === 0) {
                    $index = str_replace('template_id_', '', $key);
                    $maxId = Record::max('id') + 1;
                    $head_id = $maxId - (int)$index;

                    $recordData = [
                        'user_id' => $userId,
                        'created_by' => $createdById,
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
                    ];

                    Record::create($recordData);
                }
            }

            // 現在時刻を取得
            $now = Carbon::now('Asia/Tokyo');

            // 出勤処理
            if ($request->input('clock_status') == '1') { // 1が出勤、0が退勤
                $attendance = Attendance::where('user_id', $userId)
                    ->where('member_id', $request->input('member_id'))
                    ->whereNull('clock_out') // まだ退勤していない
                    ->orderBy('clock_in', 'desc')
                    ->first();

                // 既に出勤していて、退勤していない場合
                if ($attendance) {
                    return redirect()->back()->withErrors('既に出勤しています。');
                }

                // 既に出勤・退勤が両方完了している場合は新しいレコードを作成
                Attendance::create([
                    'user_id' => $userId,
                    'member_id' => $request->input('member_id'),
                    'clock_in' => $now,
                    'clock_out' => null,
                    'attendance' => $request->input('attendance'),
                    'attendance_date' => $now->toDateString(),
                ]);

            } else { // 退勤処理 (clock_status == 0)
                $attendance = Attendance::where('user_id', $userId)
                    ->where('member_id', $request->input('member_id'))
                    ->whereNull('clock_out')
                    ->orderBy('clock_in', 'desc')
                    ->first();

                // 出勤しているが退勤していない場合
                if ($attendance) {
                    $attendance->update(['clock_out' => $now]);
                } else {
                    return redirect()->back()->withErrors('既に退勤しています。');
                }
            }

            // トランザクションのコミット
            DB::commit();

        } catch (\Exception $e) {
            // エラーが発生した場合、ロールバック
            DB::rollBack();
            return redirect()->back()->withErrors('エラーが発生しました。もう一度やり直してください。');
        }

        // 成功時のリダイレクト
        return redirect()->route('records.index')->with('success', '勤怠情報が正常に登録されました。');
    }




// =====ここ以降は、adminのみなので、注意事項を反映しない。=====

    public function show(Record $record)
    {
        return view('records.show', compact('record'));
    }



    public function edit(Record $record)
    {
        $userId = Auth::id();
        $records = Record::with('user')->latest()->get();
        $templates = Template::where('user_id', $userId)->latest()->get();
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $jsonRecords = json_encode($records, JSON_UNESCAPED_UNICODE);
        $members = Member::where('user_id', $userId)->latest()->get();
        return view('records.edit', compact('record', 'templates', 'members', 'jsonTemplates', 'jsonRecords'));
    }

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

    public function destroy(Record $record)
    {
        // 取得したidと一致するhead_idを持つ、レコードを全て削除
        Record::where('head_id', $record->id)->delete();
        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }
}
