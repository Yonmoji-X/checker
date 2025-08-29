<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'content',
        'is_visible',
    ];

        /**
     * キャスト設定
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];


    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // public function member()
    // {
    //     return $this->belongsTo(Member::class);
    // }

    // =======↓追記した=======
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // =======↑追記した=======

    public function breakSessions()
    {
        return $this->hasMany(BreakSession::class);
    }
}
