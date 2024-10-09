<?php
namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $memberId; // メンバーIDを保持

    public function __construct($memberId = null)
    {
        $this->memberId = $memberId; // コンストラクタでメンバーIDを受け取る
    }

    public function collection()
    {
        // クエリを作成
        $query = Attendance::with(['member', 'breakSessions']); // member リレーションを追加

        // メンバーIDが指定されている場合はフィルタリング
        if ($this->memberId) {
            $query->where('member_id', $this->memberId);
        }

        // データを取得し、mapで必要な形式に変換
        return $query->get()->map(function($attendance) {
            // 出勤時刻と退勤時刻をCarbonインスタンスに変換
            $clockIn = Carbon::parse($attendance->clock_in);
            $clockOut = Carbon::parse($attendance->clock_out);

            // 出勤から退勤までの時間差を計算（分単位）
            $workDuration = $clockIn->diffInMinutes($clockOut);

            // 休憩時間（合計）を取得（分単位）
            $breakDuration = $attendance->breakSessions->sum('break_duration');

            // 労働時間を計算（総労働時間から休憩時間を引く）
            $actualWorkDuration = $workDuration - $breakDuration;

            // 労働時間がマイナスにならないようにする
            if ($actualWorkDuration < 0) {
                $actualWorkDuration = 0;
            }

            // 労働時間を「時間」形式に変換（時間と分に分ける）
            $hours = floor($actualWorkDuration / 60);
            $minutes = $actualWorkDuration % 60;
            $formattedWorkDuration = sprintf('%d時間 %d分', $hours, $minutes);

            // 労働時間（時間単位）を計算（分を時間に換算、小数点以下も表示）
            $workDurationInHours = round($actualWorkDuration / 60, 2);

            return [
                '氏名' => $attendance->member->name, // メンバー名を追加
                '日付' => $attendance->attendance_date,
                '出勤時刻' => $attendance->clock_in,
                '退勤時刻' => $attendance->clock_out,
                '休憩時間' => $breakDuration . ' 分', // 分単位の休憩時間
                '実質労働' => $formattedWorkDuration, // 時間単位の労働時間（例: 2時間 30分）
                '実質労働（時間）' => $workDurationInHours, // 小数点以下の時間単位（例: 2.5時間）
                '備考・作業内容' => $attendance->attendance,
            ];
        });
    }

    public function headings(): array
    {
        return [
            '氏名', // ヘッダーにメンバー名を追加
            '日付', // ヘッダーに日付を追加
            '出勤時刻',
            '退勤時刻',
            '休憩時間（分）',
            '労働時間',
            '労働時間（時間）', // 労働時間を時間単位で表示するカラム
            '備考・作業内容',
        ];
    }
}
