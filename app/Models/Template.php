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
        'user_id', // recordsのuser_idは投稿者ではなく管理者のidにしたい
        'member_status',
        'clock_status',
        'title',
        'has_check',
        'has_photo',
        'has_content',
        'has_temperature',
        'order',
        'hide',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);//Templateの$thisがUserに保有されている（belong to）
    }
// #####################Controller参照#####################
// 危険NG→head_idのテンプレだったら詰む。
// →recordデータのtemplate_idをnullありにして、viewで削除されましたってするくらいがいい。
    public function records()
    {
        return $this->hasMany(Record::class, 'template_id');
    }
// #####################Controller参照#####################

}


/**
 * メモ
 * $fillableはアプリケーション側から変更できるカラム  （ホワイトリスト）
 * $guarded はアプリケーション側から変更できないカラム（ブラックリスト）
 */
