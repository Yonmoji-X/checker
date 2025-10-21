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


    // Stripeプランで名簿の人数制限を全ページに適応するためのフィルタ
    public function scopeWithPlanLimit($query, $user = null) {
        $user = $user ?? auth()->user();
        if ($user->role !== 'admin') {
            return $query;
        }
        $planKey = $user->stripe_plan;
        $plan = collect(config('stripe.plans_list'))->firstWhere('stripe_plan', $planKey);
        $limit = $plan['limit'] ?? null;
    
        if ($limit !== null) {
            // 古いデータ順に取得して上限に制限
            $query->orderBy('created_at', 'asc')->limit($limit);
        }
    
        return $query;
    }
}
