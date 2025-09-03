<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'member_id',
        'attendance_date',
        'clock_in',
        'clock_out',
        'break_minutes',
        'remarks',
        'status',
        'reason',
    ];

    protected $casts = [
        'attendance_date' => 'datetime', // ここで Carbon に変換
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

}
