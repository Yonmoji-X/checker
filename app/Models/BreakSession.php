<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by',
        'member_id',
        'attendance_id',
        'attendance_request_id', // 申請データのID
        'break_in',
        'break_out',
        'break_duration',
    ];

    /**
     * Get the user associated with the break session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the member associated with the break session.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the attendance associated with the break session.
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function attendanceRequest()
    {
        return $this->belongsTo(AttendanceRequest::class);
    }
}
