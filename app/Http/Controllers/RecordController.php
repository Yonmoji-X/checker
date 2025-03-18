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
        // $templates = Template::where('user_id', $userId)->latest()->get();
        $templates = Template::where('user_id', $userId)
        ->orderBy('order') // orderカラムで昇順にソート
        ->latest()
        ->get();

        // JSONエンコード
        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $jsonRecords = json_encode($records, JSON_UNESCAPED_UNICODE);

        // メンバーを取得
        $members = Member::where('user_id', $userId)->latest()->get();

        // ------------

        // ------------

        // メンバー情報をJSON形式でエンコード
        $jsonMembers = json_encode($members, JSON_UNESCAPED_UNICODE);
        // dd($attendanceData);
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
        // $templates = Template::where('user_id', $userId)->latest()->get();
        // hideが0のテンプレートを取得
        $templates = Template::where('user_id', $userId)->where('hide', 0)->latest()->get();

        $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
        $members = Member::where('user_id', $userId)->latest()->get();
        // 各メンバーの最新の出勤データを取得
        // ============================================
        $attendanceData = [];
        foreach ($members as $member) {
            $latestAttendance = Attendance::where('member_id', $member->id)
                ->orderBy('created_at', 'desc')
                ->first();

            // 出勤状態を判定
            $status = null;
            if ($latestAttendance) {
                if ($latestAttendance->clock_in && !$latestAttendance->clock_out) {
                    $status = '（出勤中）';
                } elseif ($latestAttendance->clock_in &&$latestAttendance->clock_out) {
                    $status = '';
                }
            }

            // メンバーIDをキーに最新の出勤データと状態を格納
            $attendanceData[$member->id] = [
                'attendance' => $latestAttendance,
                'status' => $status,
            ];
        }
        // dd($attendanceData);
        // ============================================

        // ビューにデータを渡す
        return view('records.create', compact('templates', 'members','attendanceData', 'jsonTemplates'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
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
            // 'clock_in_time' => 'required|date_format:H:i:s',
            'clock_in_time' => 'required|date_format:H:i:s',
        ]);
        // dd($validated->all());
        // 現在のユーザーIDを取得
        $userId = Auth::id();
        $createdById = $userId;
        $currentUser = Auth::user();

        // `user` ロールの場合、`admin_id` に切り替え
        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // 現在時刻
        $now = Carbon::now('Asia/Tokyo');

        // jsからページロード時間を取得→出勤時間
        $clockInTime = $request->input('clock_in_time');
        // dd($request->all());
        // dd($clockInTime);
        if ($clockInTime == null) {
            $clockInTime = $now;
        }

        // トランザクションの開始
        DB::beginTransaction();

        try {
            $attendance = Attendance::where('user_id', $userId)
                ->where('member_id', $request->input('member_id'))
                ->whereNull('clock_out')
                ->orderBy('clock_in', 'desc')
                ->first();

            // 出勤処理
            if ($request->input('clock_status') == '1') {
                if ($attendance) {
                    return redirect()->back()->withErrors('既に出勤しています。');
                }

                // 新規出勤データを作成
                $attendance = Attendance::create([
                    'user_id' => $userId,
                    'member_id' => $request->input('member_id'),
                    'clock_in' => $clockInTime,
                    'clock_out' => null,
                    'attendance' => $request->input('attendance'),
                    'attendance_date' => $now->toDateString(),
                ]);
            }
            // 退勤処理
            else {
                if (!$attendance) {
                    return redirect()->back()->withErrors('既に退勤しています。');
                }
                $attendance->update(['clock_out' => $now]);
            }

            // Recordデータを保存
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
                        'attendance_id' => $attendance->id ?? null, // Attendance IDをセット
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

            // トランザクションのコミット
            DB::commit();

        } catch (\Exception $e) {
            // エラーが発生した場合、ロールバック
            DB::rollBack();
            return redirect()->back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }

        // 成功時のリダイレクト
        return redirect()->route('attendances.index')->with('success', '勤怠情報が正常に登録されました。');
    }





// =====ここ以降は、adminのみなので、注意事項を反映しない。=====

    public function show(Record $record)
    {
        return view('records.show', compact('record'));
    }

    public function edit(Record $record)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) {
                $userId = $group->admin_id;
            }
        }

        // 指定されたレコードに関連するテンプレートとメンバーを取得
        $template = Template::where('id', $record->template_id)->first(); // 単一のテンプレートを取得
        $member = Member::where('id', $record->member_id)->get();

        // JSONエンコード
        $jsonTemplate = json_encode($template, JSON_UNESCAPED_UNICODE);
        $jsonMember = json_encode($member, JSON_UNESCAPED_UNICODE);

        return view('records.edit', compact('record', 'template', 'member', 'jsonTemplate', 'jsonMember'));
    }





    public function update(Request $request, Record $record)
    {
        // 管理者のみが更新可能
        // Gate::authorize('isAdmin');

        // バリデーションルールを定義
        $request->validate([
            'check_item' => 'nullable|in:0,1', // "0" または "1" を許可
            'content_item' => 'nullable|string|max:255',
            'photo_item' => 'nullable|file|image|max:2048',
            'temperature_item' => 'nullable|numeric',
        ]);

        // リクエストからデータを取得
        $data = $request->only([
            'check_item',
            'content_item',
            'temperature_item'
        ]);

        // もし photo_item がアップロードされていれば、保存処理を行う
        if ($request->hasFile('photo_item')) {
            // 古いファイルを削除する場合
            if ($record->photo_item) {
                Storage::delete($record->photo_item);
            }

            // 新しいファイルを保存
            $path = $request->file('photo_item')->store('public/photos');
            $data['photo_item'] = $path;
        } else {
            // photo_item がnullであれば何もしない
            unset($data['photo_item']);
        }

        // レコードを更新
        $record->update($data);

        // 成功メッセージと共にリダイレクト
        return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }


    public function destroy(Record $record)
    {
        // 取得したidと一致するhead_idを持つ、レコードを全て削除
        Record::where('head_id', $record->id)->delete();
        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }

    // public function updateOrder(Request $request)
    // {
    //     $sortedIDs = $request->input('sortedIDs');

    //     // 並び替えたIDに基づいてorderカラムを更新
    //     foreach ($sortedIDs as $order => $id) {
    //         Record::where('id', $id)->update(['order' => $order]);
    //     }

    //     return response()->json(['success' => true]); // 成功のレスポンスを返す
    // }

}
