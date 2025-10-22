<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;

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


    // Stripeプランで名簿の人数制限を全ページに適応するためのフィルタ
    public function scopeWithPlanLimit($query, $user = null) 
    {
        $user = $user ?? auth()->user(); // User オブジェクトを期待

        // ユーザーが ID なら取得する
        if (is_int($user)) {
            $user = \App\Models\User::find($user);
        }

        // ここでまだ null の可能性があるので安全策
        if (!$user) {
            return $query; // ユーザー不明なら制限なしで返す
        }

        // もし currentUser が一般ユーザーなら、グループ管理者の User オブジェクトを取得
        $currentUser = auth()->user();
        if ($currentUser->role === 'user') {
            $group = Group::where('user_id', $user->id)->first();
            if ($group) {
                $adminUser = \App\Models\User::find($group->admin_id);
                if ($adminUser) {
                    $user = $adminUser; // 管理者ユーザーに差し替え
                }
            }
        }

        $planKey = $user->stripe_plan ?? null;
        $plan = collect(config('stripe.plans_list'))->firstWhere('stripe_plan', $planKey);
        $limit = $plan['limit'] ?? null;

        if ($limit !== null) {
            $query->orderBy('created_at', 'asc')->limit($limit);
        }

        return $query;
    }


}
