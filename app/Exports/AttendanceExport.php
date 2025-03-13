<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Record;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $memberId;
    protected $startDate;
    protected $endDate;

    public function __construct($memberId = null, $startDate = null, $endDate = null)
    {
        $this->memberId = $memberId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function collection()
    {
        // クエリを作成（recordもリレーション取得）
        $query = Attendance::with(['member', 'breakSessions', 'records']);

        // メンバーIDが指定されている場合はフィルタリング
        if ($this->memberId) {
            $query->where('member_id', $this->memberId);
        }

        // 日付範囲が指定されている場合
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('attendance_date', [$this->startDate, $this->endDate]);
        }

        // データを取得し、必要な形式に変換
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

            // recordテーブルのcontent_itemカラムを取得（複数ある場合は改行で結合）
            $contentItems = $attendance->records->pluck('content_item')->implode("\n");

            return [
                '氏名' => $attendance->member->name,
                '日付' => $attendance->attendance_date,
                '出勤時刻' => $attendance->clock_in,
                '退勤時刻' => $attendance->clock_out,
                '休憩時間' => $breakDuration . ' 分',
                '実質労働' => $formattedWorkDuration,
                '実質労働（時間）' => $workDurationInHours,
                '備考・作業内容' => $attendance->attendance,
                '作業内容' => $contentItems, // 新しく追加
            ];
        });
    }

    public function headings(): array
    {
        return [
            '氏名',
            '日付',
            '出勤時刻',
            '退勤時刻',
            '休憩時間（分）',
            '労働時間',
            '労働時間（時間）',
            '備考・作業内容',
            '作業内容', // 新しく追加
        ];
    }

}
