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
    // public function index()
    // {
    //     // 現在のユーザーIDを取得
    //     $userId = Auth::id();

    //     // 現在のログインユーザー情報を取得
    //     $currentUser = Auth::user();

    //     // ユーザーがuser roleの場合
    //     if ($currentUser->role === 'user') {
    //         // groupテーブルから現在のユーザーIDに関連するadmin_idを取得
    //         $group = Group::where('user_id', $userId)->first();

    //         // groupが存在すれば、admin_idを$userIdとして使用
    //         if ($group) {
    //             $userId = $group->admin_id;
    //         }
    //     }

    //     // 指定した$userIdでレコードを取得
    //     $records = Record::with('user')->where('user_id', $userId)->latest()->get();
    //     // $templates = Template::where('user_id', $userId)->latest()->get();
    //     $templates = Template::where('user_id', $userId)
    //     ->orderBy('order') // orderカラムで昇順にソート
    //     ->latest()
    //     ->get();

    //     // JSONエンコード
    //     $jsonTemplates = json_encode($templates, JSON_UNESCAPED_UNICODE);
    //     $jsonRecords = json_encode($records, JSON_UNESCAPED_UNICODE);

    //     // メンバーを取得
    //     $members = Member::where('user_id', $userId)->where('is_visible', 1)
    //     ->latest()
    //     ->get();

    //     // ------------

    //     // ------------

    //     // メンバー情報をJSON形式でエンコード
    //     $jsonMembers = json_encode($members, JSON_UNESCAPED_UNICODE);
    //     // dd($attendanceData);
    //     // ビューにデータを渡す
    //     return view('records.index', compact('records', 'templates', 'members', 'jsonTemplates', 'jsonRecords', 'jsonMembers'));
    // }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        if ($currentUser->role == 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) $userId = $group->admin_id;
        }

        $members = Member::where('user_id', $userId)
                        ->where('is_visible', 1)
                        ->get();

        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date   ?? now()->endOfMonth()->format('Y-m-d');
        $templateMemberStatus = $request->member_status ?? 0;
        $templateClockStatus  = $request->clock_status ?? 1;

        // head_id ごとにグループ化
        $recordsQuery = Record::where('user_id', $userId)
            ->with(['member', 'template', 'attendance'])
            ->whereHas('attendance', fn($q) => $q
            ->whereDate('attendance_date', '>=', $startDate)
            ->whereDate('attendance_date', '<=', $endDate))
            ->orderByDesc('created_at');

        $records = $recordsQuery->get()->groupBy('head_id');

        // ページネーション
        $page = $request->get('page', 1);
        $perPage = 5;
        $pagedData = $records->forPage($page, $perPage);

        $paginatedGroups = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedData,
            $records->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $templates = Template::where('user_id', $userId)
                            ->where('member_status', $templateMemberStatus)
                            ->get();

        return view('records.index', compact(
            'members',
            'paginatedGroups',
            'templates',
            'startDate',
            'endDate',
            'templateMemberStatus',
            'templateClockStatus'
        ));
    }

    public function filter(Request $request)
    {
        $userId = Auth::id();
        $currentUser = Auth::user();

        if ($currentUser->role == 'user') {
            $group = Group::where('user_id', $userId)->first();
            if ($group) $userId = $group->admin_id;
        }

        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date   ?? now()->endOfMonth()->format('Y-m-d');
        $memberId  = $request->member_id ?? null;
        $templateMemberStatus = $request->member_status ?? 0;
        $templateClockStatus  = $request->clock_status ?? 1;

        // head_id 単位でページネーション用に取得
        $headIdsQuery = Record::where('user_id', $userId)
            ->when($memberId, fn($q) => $q->where('member_id', $memberId))
            ->whereHas('attendance', fn($q) => $q->whereDate('attendance_date', '>=', $startDate)
                                                ->whereDate('attendance_date', '<=', $endDate))
            ->when($templateClockStatus !== null, fn($q) => $q->where('clock_status', $templateClockStatus))
            ->select('head_id')
            ->groupBy('head_id')
            ->orderByRaw('MAX(created_at) DESC');

        $headIds = $headIdsQuery->paginate(5)->withQueryString();

        // head_id に該当する records をまとめて取得
        $records = Record::where('user_id', $userId)
            ->with(['member', 'template', 'attendance'])
            ->whereIn('head_id', $headIds->pluck('head_id'))
            ->get()
            ->groupBy('head_id');

        $templates = Template::where('user_id', $userId)
                            ->where('member_status', $templateMemberStatus)
                            ->get();

        $rows = view('records._table_rows', ['recordsGroups' => $records, 'templates' => $templates])->render();
        $pagination = view('records._pagination', ['headIds' => $headIds])->render();

        return response()->json([
            'rows' => $rows,
            'pagination' => $pagination,
        ]);
    }




    // public function filter(Request $request) 
    // {
    //     $userId = Auth::id();
    //     $currentUser = Auth::id();

    //     if ($curerntUser->role === 'user_id') {
    //         $group = Group::where('user_id', $userId)->first();
    //         if ($group) $userId = $group->admin_id;
    //     }

    //     $query = Record::with('template')
    //         ->where('user_id', $userId)

    //     if ($request->member_status !== null)  {
    //         $query = where('member_status', $request->member_status);
    //     }

    //     if ($request->clock_status !== null)  {
    //         $query = where('clock_status', $request->clock_status);
    //     }

    //     if ($request->member_id !== null) {
    //         $query = where('member_id', $request->member_id);
    //     }

    //     if ($request->start_date) {
    //         $query = whereDate('created_at', '>=', $start_date);
    //     }

    //     if ($request->end_date) {
    //         $query = whereDate('created_at', '<=', $end_date);
    //     }

    //     $records = $query
    //             ->orderBy('created_at', 'desc')
    //             ->get()

    //     return response()->json($records);
    // }

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
            'template_id_*' => 'nullable|integer',
            // 'template_id_*' => 'required|integer',
            'check_item_*' => 'nullable|boolean',
            'photo_item_*' => 'nullable|file|image|max:2048',
            'content_item_*' => 'nullable|string|max:255',
            'temperature_item_*' => 'nullable|numeric',
            // 'clock_in_time' => 'required|date_format:H:i:s',
            'clock_in_time' => 'nullable|date_format:H:i:s',
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

        // // jsからページロード時間を取得→出勤時間
        // $clockInTime = $request->input('clock_in_time');
        // // dd($request->all());
        // // dd($clockInTime);
        // if ($clockInTime == null) {
        //     $clockInTime = $now;
        // }

        // 出勤時間はサーバー時間を使う
        // $clockInTime = $now;
        $clockInTime = $request->input('clock_in_time');
        if (!$clockInTime) {
            $clockInTime = now()->format('H:i:s'); // サーバー時間をセット
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

                // 退勤時、休憩終了してなかったら、退勤時間を休憩終了時間に入れる。
                $latestBreak = \App\Models\BreakSession::where('user_id', $userId)
                ->where('member_id', $request->input('member_id')) // メンバーごとの休憩を対象
                ->whereNull('attendance_request_id') // 申請の休憩データは無視
                ->whereNotNull('break_in')           // break_inはある
                ->whereNull('break_out')             // break_outがない
                ->latest()
                ->first();

                if ($latestBreak) {
                    $latestBreak->update([
                        'break_out' => $now,
                        'break_duration' => \Carbon\Carbon::parse($latestBreak->break_in)->diffInMinutes($now),
                    ]);
                }
                // if ($latestBreak && $latestBreak->break_in && !$latestBreak->break_out) {
                //     $latestBreak->break_out = $now;
                //     $latestBreak->save();
                // }
            }
        
            // Recordデータを保存
            $templateIds = collect($request->all())
                ->filter(fn($v, $k) => str_starts_with($k, 'template_id_'));
            // dd($templateIds);
            // dd($templateIds, $templateIds->toArray(), $templateIds->isEmpty());

            if ($templateIds->isEmpty()) {
                // ⭐ template_id がない場合は登録せず、警告メッセージを返す
                // return redirect()->back()->withErrors('アイテムを作成してください。');
                // templateがない場合でも最低限のRecordを作成
                // ⭐ テンプレートがない場合は最低限の Record を作成
                Record::create([
                    'user_id'       => $userId,
                    'created_by'    => $createdById,
                    'member_id'     => $request->input('member_id'),
                    'template_id'   => null, // 無し
                    'member_status' => $request->input('member_status'),
                    'clock_status'  => $request->input('clock_status'),
                    'attendance_id' => $attendance->id ?? null,
                    'head_id'       => Record::max('id') + 1,
                ]);
            } else {
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
