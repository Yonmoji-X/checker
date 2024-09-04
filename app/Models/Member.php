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
        'content'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function member()
    // {
    //     return $this->belongsTo(Member::class);
    // }

    // public function attendance()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
