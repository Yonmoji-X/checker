<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    // このモデルが許可する属性を指定します
    protected $fillable = [
        'user_id',
        'member_id',
        'template_id',
        'member_status', // "memger_status"を"member_status"に修正
        'clock_status',
        'check_item',
        'photo_item',
        'content_item',
        'head_id',
        'temperature_item',
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // メンバーとのリレーション
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // テンプレートとのリレーション
    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
