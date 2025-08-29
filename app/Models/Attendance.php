<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'clock_in',
        'clock_out',
        'attendance',
        'attendance_date',
    ];

    /**
     * Get the user associated with the attendance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the member associated with the attendance.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function breakSessions()
    {
        return $this->hasMany(BreakSession::class);
    }
    

    // public function records()
    // {
    //     return $this->hasMany(Record::class);
    // }
    public function records()
    {
        return $this->hasMany(Record::class, 'attendance_id', 'id');
    }

}
