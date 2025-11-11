<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Notifications\Notifiable;

use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 関連データとの連携を定義
     * 今回の連携
     * [Userモデル：Templateモデル]→[1：多]
     * Userモデルから見ると、Templateモデルの関係は1：多のため、templates()を作成
     */
    public function templates()
    {
        return $this->hasMany(Template::class); //Userモデルの $thisに対して、Templateモデルは多数（hasMany)
    }
    public function members()
    {
        return $this->hasMany(Member::class); //Userモデルの $thisに対して、Memberモデルは多数（hasMany)
    }

    public function records()
    {
        return $this->hasMany(Record::class); //Userモデルの $thisに対して、recordモデルは多数（hasMany)
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function breakSessions()
    {
        return $this->hasMany(BreakSession::class);
    }

    //ここから追加
    public function sendPasswordResetNotification($token)
    {
        $url = url("reset-password/${token}");
        $this->notify(new ResetPasswordNotification($url));
    }
    //ここまで追加
}
