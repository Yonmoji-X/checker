<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Record;
use App\Models\Template;
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
        $query = Attendance::with(['member', 'breakSessions', 'records.template']);

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
            // $hours = floor($actualWorkDuration / 60);
            // $minutes = $actualWorkDuration % 60;
            // $formattedWorkDuration = sprintf('%d時間 %d分', $hours, $minutes);

            // 労働時間を「Excel 関数」形式で出力
            // $rowNumber = 'ROW()'; // Excel 側で自動的に行番号を取得
            $formattedWorkDuration = '=INT(INDIRECT("G"&ROW())) & "時間" & ROUND((INDIRECT("G"&ROW()) - INT(INDIRECT("G"&ROW()))) * 60, 0) & "分"';


            // 労働時間（時間単位）を計算（分を時間に換算、小数点以下も表示）
            // $workDurationInHours = round($actualWorkDuration / 60, 2);

            // 労働時間（時間単位）も Excel 関数
            $workDurationInHours = '=ROUND(HOUR(INDIRECT("D"&ROW()) - INDIRECT("C"&ROW())) +
       MINUTE(INDIRECT("D"&ROW()) - INDIRECT("C"&ROW()))/60 +
       SECOND(INDIRECT("D"&ROW()) - INDIRECT("C"&ROW()))/3600 -
       SUBSTITUTE(INDIRECT("E"&ROW()), "分", "")/60, 2)';

            // templateのexportが1のrecordのみ取得
            $contentItems = $attendance->records
                ->filter(function($record) {
                    return $record->template && $record->template->export == 1;
                })
                ->pluck('content_item')
                ->implode("\n");


            // 返却データの作成
            $data = [
                '氏名' => $attendance->member->name,
                '日付' => $attendance->attendance_date,
                '出勤時刻' => $attendance->clock_in,
                '退勤時刻' => $attendance->clock_out,
                '休憩時間' => $breakDuration . ' 分',
                '実質労働' => $formattedWorkDuration,
                '実質労働（時間）' => $workDurationInHours,
                '備考・作業内容' => $attendance->attendance,
            ];

            // 作業内容がある場合のみ追加
            if (!empty($contentItems)) {
                $data['作業内容'] = $contentItems;
            }

            return $data;
        });
    }

    public function headings(): array
    {
        // '作業内容' を動的に追加するために、データが含まれるかどうかで決定
        $headings = [
            '氏名',
            '日付',
            '出勤時刻',
            '退勤時刻',
            '休憩時間（分）',
            '労働時間',
            '労働時間（時間）',
            '備考・作業内容',
        ];

        // どれかのデータに作業内容が含まれている場合は追加
        if (Record::whereHas('template', function($q) {
            $q->where('export', 1);
        })->exists()) {
            $headings[] = '作業内容';
        }


        return $headings;
    }

}
