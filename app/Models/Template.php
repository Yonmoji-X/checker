<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    /**
     * TemplateモデルにUserモデルとの連携を定義する。
     * userメソッド（user())を追加する。
     * [Templateモデル：Userモデル]→[多：1]
     * $fillableにtemplateを追加する。
     * $fillableにはユーザーから受け付けるカラムを指定する。
     */
    protected $fillable = [
        'user_id', // ユーザーID
        'member_status',
        'clock_status',
        'title',
        'has_check',
        'has_photo',
        'has_content',
        'has_temperature',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);//Templateの$thisがUserに保有されている（belong to）
    }
}


/**
 * メモ
 * $fillableはアプリケーション側から変更できるカラム  （ホワイトリスト）
 * $guarded はアプリケーション側から変更できないカラム（ブラックリスト）
 */
